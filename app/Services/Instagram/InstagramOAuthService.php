<?php

namespace App\Services\Instagram;

use App\Models\InstagramConnection;
use App\Models\InstagramSetting;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use RuntimeException;

class InstagramOAuthService
{
    public function settings(): InstagramSetting
    {
        return InstagramSetting::current();
    }

    public function isConfigured(): bool
    {
        return $this->settings()->isConfigured();
    }

    public function authorizationUrl(string $state): string
    {
        $this->ensureConfigured();
        $settings = $this->settings();

        return 'https://www.facebook.com/'.config('instagram.graph_version').'/dialog/oauth?'.http_build_query([
            'client_id' => $settings->resolvedAppId(),
            'redirect_uri' => $settings->redirectUri(),
            'scope' => implode(',', config('instagram.scopes')),
            'response_type' => 'code',
            'state' => $state,
        ]);
    }

    /**
     * Exchange OAuth code for a stored Instagram connection.
     *
     * @throws RequestException
     * @throws RuntimeException
     */
    public function connectWithCode(string $code): InstagramConnection
    {
        $this->ensureConfigured();
        $settings = $this->settings();

        $shortLived = $this->exchangeCodeForToken($code);
        $longLived = $this->exchangeForLongLivedToken($shortLived['access_token']);
        $account = $this->resolveInstagramBusinessAccount($longLived['access_token']);

        InstagramConnection::query()->where('is_active', true)->update(['is_active' => false]);

        return InstagramConnection::query()->create([
            'instagram_user_id' => $account['instagram_user_id'],
            'username' => $account['username'],
            'page_id' => $account['page_id'],
            'page_name' => $account['page_name'],
            'access_token' => $account['page_access_token'],
            'token_expires_at' => isset($longLived['expires_in'])
                ? now()->addSeconds((int) $longLived['expires_in'])
                : null,
            'sync_limit' => $settings->resolvedSyncLimit(),
            'is_active' => true,
        ]);
    }

    public function disconnect(?InstagramConnection $connection = null): void
    {
        $connection ??= InstagramConnection::current();
        $connection?->delete();
    }

    /**
     * @return array{access_token: string, token_type?: string}
     */
    protected function exchangeCodeForToken(string $code): array
    {
        $settings = $this->settings();

        $response = Http::asForm()->get($this->graphUrl('/oauth/access_token'), [
            'client_id' => $settings->resolvedAppId(),
            'client_secret' => $settings->resolvedAppSecret(),
            'redirect_uri' => $settings->redirectUri(),
            'code' => $code,
        ])->throw();

        return $response->json();
    }

    /**
     * @return array{access_token: string, token_type?: string, expires_in?: int}
     */
    protected function exchangeForLongLivedToken(string $shortLivedToken): array
    {
        $settings = $this->settings();

        $response = Http::get($this->graphUrl('/oauth/access_token'), [
            'grant_type' => 'fb_exchange_token',
            'client_id' => $settings->resolvedAppId(),
            'client_secret' => $settings->resolvedAppSecret(),
            'fb_exchange_token' => $shortLivedToken,
        ])->throw();

        return $response->json();
    }

    /**
     * @return array{
     *     instagram_user_id: string,
     *     username: ?string,
     *     page_id: string,
     *     page_name: ?string,
     *     page_access_token: string
     * }
     */
    protected function resolveInstagramBusinessAccount(string $userAccessToken): array
    {
        $pages = Http::get($this->graphUrl('/me/accounts'), [
            'fields' => 'id,name,access_token,instagram_business_account',
            'access_token' => $userAccessToken,
        ])->throw()->json('data', []);

        foreach ($pages as $page) {
            $igAccountId = data_get($page, 'instagram_business_account.id');

            if (blank($igAccountId)) {
                continue;
            }

            $username = Http::get($this->graphUrl('/'.$igAccountId), [
                'fields' => 'id,username',
                'access_token' => $page['access_token'],
            ])->throw()->json('username');

            return [
                'instagram_user_id' => (string) $igAccountId,
                'username' => $username,
                'page_id' => (string) $page['id'],
                'page_name' => $page['name'] ?? null,
                'page_access_token' => $page['access_token'],
            ];
        }

        throw new RuntimeException(
            'No Instagram Professional account was found. Link a Business/Creator Instagram account to a Facebook Page, then try again.'
        );
    }

    protected function graphUrl(string $path): string
    {
        return 'https://graph.facebook.com/'.config('instagram.graph_version').'/'.ltrim($path, '/');
    }

    protected function ensureConfigured(): void
    {
        if (! $this->isConfigured()) {
            throw new RuntimeException(
                'Instagram is not configured. Save your Meta App ID and App Secret on the Instagram page first.'
            );
        }
    }
}
