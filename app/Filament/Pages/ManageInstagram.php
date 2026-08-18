<?php

namespace App\Filament\Pages;

use App\Models\InstagramConnection;
use App\Models\InstagramPost;
use App\Models\InstagramSetting;
use App\Services\Instagram\InstagramMediaSyncService;
use App\Services\Instagram\InstagramOAuthService;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Callout;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Html;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Throwable;
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageInstagram extends Page
{
    use CanUseDatabaseTransactions;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Settings';

    protected static string|UnitEnum|null $navigationGroup = 'Instagram';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Instagram Integration';

    protected string $view = 'filament.pages.manage-instagram';

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $settings = InstagramSetting::current();

        $this->form->fill([
            'app_id' => $settings->app_id,
            'app_secret' => $settings->app_secret,
            'sync_limit' => $settings->sync_limit ?: 12,
            'redirect_uri_display' => $settings->redirectUri(),
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->model(InstagramSetting::current())
            ->operation('edit')
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Meta App credentials')
                    ->description('From Meta Developer Console → App settings → Basic. Then connect the Instagram account.')
                    ->icon(Heroicon::OutlinedKey)
                    ->schema([
                        TextInput::make('app_id')
                            ->label('App ID')
                            ->placeholder('e.g. 123456789012345')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('app_secret')
                            ->label('App Secret')
                            ->password()
                            ->revealable()
                            ->required()
                            ->maxLength(255),
                        TextInput::make('sync_limit')
                            ->label('Posts to sync')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(50)
                            ->required()
                            ->helperText('How many latest posts to pull on each sync (1–50).'),
                        TextInput::make('redirect_uri_display')
                            ->label('OAuth Redirect URI')
                            ->disabled()
                            ->dehydrated(false)
                            ->helperText('Copy this into Meta → Facebook Login → Settings → Valid OAuth Redirect URIs.'),
                    ])
                    ->columns(2),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([EmbeddedSchema::make('form')])
                    ->id('form')
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save credentials')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),

                $this->getConnectionCallout(),
                $this->getSyncedFeedSection(),
            ]);
    }

    protected function getConnectionCallout(): Callout
    {
        $connection = InstagramConnection::current();
        $isConfigured = app(InstagramOAuthService::class)->isConfigured();
        $syncedCount = InstagramPost::query()->count();

        if ($connection?->isConnected()) {
            $message = new HtmlString(
                '<div class="space-y-1 text-sm">'
                .'<div><strong>Account:</strong> @'.e($connection->username ?? 'unknown').'</div>'
                .'<div><strong>Facebook Page:</strong> '.e($connection->page_name ?? '—').'</div>'
                .'<div><strong>Synced images:</strong> '.$syncedCount.'</div>'
                .'<div><strong>Last sync:</strong> '.e($connection->last_synced_at?->diffForHumans() ?? 'Never')
                .(filled($connection->last_sync_status) ? ' ('.e($connection->last_sync_status).')' : '')
                .'</div>'
                .(filled($connection->last_sync_message)
                    ? '<div><strong>Message:</strong> '.e($connection->last_sync_message).'</div>'
                    : '')
                .'</div>'
            );

            return Callout::make('Connected')
                ->success()
                ->icon(Heroicon::OutlinedCheckCircle)
                ->description($message);
        }

        if ($isConfigured) {
            return Callout::make('Ready to connect')
                ->info()
                ->icon(Heroicon::OutlinedLink)
                ->description('Credentials are saved. Click Connect Instagram above to authorize a Professional (Business/Creator) account linked to a Facebook Page.');
        }

        return Callout::make('Not configured yet')
            ->warning()
            ->icon(Heroicon::OutlinedExclamationTriangle)
            ->description('Enter App ID + App Secret above, click Save credentials, then Connect Instagram will appear.');
    }

    protected function getSyncedFeedSection(): Section
    {
        $items = InstagramPost::query()
            ->orderBy('sort_order')
            ->get();

        $description = $items->isEmpty()
            ? 'Images from Instagram will appear here after you connect and sync.'
            : $items->count().' synced posts · saved in instagram_posts · storage/app/public/image/instagram/';

        return Section::make('Synced Instagram feed')
            ->description($description)
            ->icon(Heroicon::OutlinedPhoto)
            ->schema([
                Html::make(new HtmlString(
                    view('filament.pages.partials.instagram-synced-feed', [
                        'items' => $items,
                    ])->render()
                )),
            ]);
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();

            $data = $this->form->getState();
            $settings = InstagramSetting::current();
            $settings->update([
                'app_id' => $data['app_id'],
                'app_secret' => $data['app_secret'],
                'sync_limit' => (int) ($data['sync_limit'] ?? 12),
            ]);

            if ($connection = InstagramConnection::current()) {
                $connection->update(['sync_limit' => $settings->resolvedSyncLimit()]);
            }

            $this->commitDatabaseTransaction();
            $this->fillForm();

            Notification::make()
                ->title('Instagram credentials saved')
                ->success()
                ->send();
        } catch (Halt $exception) {
            $exception->shouldRollbackDatabaseTransaction()
                ? $this->rollBackDatabaseTransaction()
                : $this->commitDatabaseTransaction();

            return;
        } catch (Throwable $exception) {
            $this->rollBackDatabaseTransaction();

            throw $exception;
        }
    }

    public function getTitle(): string|Htmlable
    {
        return 'Instagram Integration';
    }

    public function getSubheading(): string|Htmlable|null
    {
        return 'Connect a Professional Instagram account and sync posts into the site gallery.';
    }

    protected function getHeaderActions(): array
    {
        $connection = InstagramConnection::current();
        $oauth = app(InstagramOAuthService::class);

        return [
            Action::make('connect')
                ->label($connection ? 'Reconnect' : 'Connect Instagram')
                ->icon(Heroicon::OutlinedLink)
                ->color('primary')
                ->url(route('admin.instagram.redirect'))
                ->visible(fn (): bool => $oauth->isConfigured()),

            Action::make('sync')
                ->label('Sync Now')
                ->icon(Heroicon::OutlinedArrowPath)
                ->color('gray')
                ->requiresConfirmation()
                ->modalHeading('Sync Instagram feed?')
                ->modalDescription('This will fetch the latest posts and update the Instagram gallery.')
                ->visible(fn (): bool => (bool) $connection?->isConnected())
                ->action(function (): void {
                    try {
                        $stats = app(InstagramMediaSyncService::class)->sync();

                        Notification::make()
                            ->title('Instagram sync complete')
                            ->body(sprintf(
                                'Imported %d, updated %d, skipped %d.',
                                $stats['imported'],
                                $stats['updated'],
                                $stats['skipped']
                            ))
                            ->success()
                            ->send();
                    } catch (Throwable $e) {
                        Notification::make()
                            ->title('Instagram sync failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                }),

            Action::make('disconnect')
                ->label('Disconnect')
                ->icon(Heroicon::OutlinedXMark)
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading('Disconnect Instagram?')
                ->modalDescription('The connection will be removed. Synced gallery images will remain until you delete them.')
                ->visible(fn (): bool => (bool) $connection?->isConnected())
                ->action(function (): void {
                    app(InstagramOAuthService::class)->disconnect();

                    Notification::make()
                        ->title('Instagram disconnected')
                        ->success()
                        ->send();
                }),
        ];
    }
}
