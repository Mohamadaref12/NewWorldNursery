<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Instagram\InstagramMediaSyncService;
use App\Services\Instagram\InstagramOAuthService;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Throwable;

class InstagramOAuthController extends Controller
{
    public function redirect(InstagramOAuthService $oauth): RedirectResponse
    {
        if (! $oauth->isConfigured()) {
            Notification::make()
                ->title('Instagram is not configured')
                ->body('Save your Meta App ID and App Secret on the Instagram page first.')
                ->danger()
                ->send();

            return redirect()->route('filament.admin.pages.manage-instagram');
        }

        $state = Str::random(40);
        session(['instagram_oauth_state' => $state]);

        return redirect()->away($oauth->authorizationUrl($state));
    }

    public function callback(Request $request, InstagramOAuthService $oauth, InstagramMediaSyncService $sync): RedirectResponse
    {
        $expectedState = session()->pull('instagram_oauth_state');

        if (! $expectedState || $request->string('state')->toString() !== $expectedState) {
            Notification::make()
                ->title('Instagram connection failed')
                ->body('Invalid OAuth state. Please try connecting again.')
                ->danger()
                ->send();

            return redirect()->route('filament.admin.pages.manage-instagram');
        }

        if ($request->filled('error')) {
            Notification::make()
                ->title('Instagram connection cancelled')
                ->body($request->string('error_description')->toString() ?: $request->string('error')->toString())
                ->warning()
                ->send();

            return redirect()->route('filament.admin.pages.manage-instagram');
        }

        $code = $request->string('code')->toString();

        if (blank($code)) {
            Notification::make()
                ->title('Instagram connection failed')
                ->body('Missing authorization code from Meta.')
                ->danger()
                ->send();

            return redirect()->route('filament.admin.pages.manage-instagram');
        }

        try {
            $connection = $oauth->connectWithCode($code);
            $stats = $sync->sync($connection);

            Notification::make()
                ->title('Instagram connected')
                ->body(sprintf(
                    'Connected as @%s. Imported %d posts.',
                    $connection->username ?? 'account',
                    $stats['imported'] + $stats['updated']
                ))
                ->success()
                ->send();
        } catch (Throwable $e) {
            Log::error('Instagram OAuth failed', ['message' => $e->getMessage()]);

            Notification::make()
                ->title('Instagram connection failed')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }

        return redirect()->route('filament.admin.pages.manage-instagram');
    }
}
