<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Throwable;

/**
 * @property-read Schema $form
 */
class ManageSiteSettings extends Page
{
    use CanUseDatabaseTransactions;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static ?int $navigationSort = 1;

    protected static ?string $title = 'Site Settings';

    protected string $view = 'filament.pages.manage-site-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $this->fillForm();
    }

    protected function fillForm(): void
    {
        $this->form->fill(SiteSetting::current()->attributesToArray());
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema
            ->model(SiteSetting::current())
            ->operation('edit')
            ->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('General')->schema([
                    TextInput::make('site_name')->required()->maxLength(255),
                    TextInput::make('top_bar_phone')->tel()->maxLength(50),
                    TextInput::make('top_bar_email')->email()->maxLength(255),
                ])->columns(3),
                Section::make('Social Media')->schema([
                    TextInput::make('facebook_url')->url()->maxLength(255),
                    TextInput::make('instagram_url')->url()->maxLength(255),
                    TextInput::make('twitter_url')->url()->maxLength(255),
                    TextInput::make('youtube_url')->url()->maxLength(255),
                ])->columns(2),
                Section::make('Hero Section')->schema([
                    TextInput::make('hero_eyebrow')->maxLength(255)->label('Eyebrow')->placeholder('NEW WORLD NURSERY - DUBAI'),
                    TextInput::make('hero_title')->maxLength(255),
                    Textarea::make('hero_subtitle')->rows(3),
                    FileUpload::make('hero_image')->image()->directory('site')->disk('public'),
                    TextInput::make('hero_cta_primary')->maxLength(100),
                    TextInput::make('hero_cta_secondary')->maxLength(100),
                ])->columns(2),
                Section::make('About Section')->schema([
                    TextInput::make('about_label')->maxLength(100)->placeholder('ABOUT US'),
                    TextInput::make('about_title')->maxLength(255),
                    TextInput::make('about_highlight')->maxLength(255)->helperText('Words to highlight e.g. New World Nursery'),
                    Textarea::make('about_content')->rows(5),
                    FileUpload::make('about_image')->image()->directory('site')->disk('public'),
                    TextInput::make('about_cta')->maxLength(100),
                ])->columns(2),
                Section::make('Section Headings')->schema([
                    TextInput::make('locations_label')->maxLength(100)->placeholder('OUR LOCATIONS'),
                    TextInput::make('locations_title')->maxLength(255),
                    TextInput::make('locations_title_highlight')->maxLength(100)->placeholder('the region'),
                    TextInput::make('locations_subtitle')->maxLength(500),
                    TextInput::make('programs_label')->maxLength(100)->placeholder('OUR PROGRAMS'),
                    TextInput::make('programs_title')->maxLength(255),
                    TextInput::make('programs_title_highlight')->maxLength(100)->placeholder('age & stage'),
                    TextInput::make('programs_subtitle')->maxLength(500),
                    TextInput::make('gallery_label')->maxLength(100)->placeholder('INSTAGRAM'),
                    TextInput::make('gallery_title')->maxLength(255),
                    TextInput::make('gallery_title_highlight')->maxLength(100)->placeholder('Our Journey'),
                    TextInput::make('gallery_subtitle')->maxLength(500),
                    TextInput::make('gallery_cta')->maxLength(100),
                ])->columns(2),
                Section::make('Contact & Footer')->schema([
                    TextInput::make('contact_label')->maxLength(100)->placeholder('PLAN A VISIT'),
                    TextInput::make('contact_title')->maxLength(255),
                    TextInput::make('contact_title_highlight')->maxLength(100)->placeholder('Our Team'),
                    TextInput::make('contact_subtitle')->maxLength(500),
                    TextInput::make('contact_email')->email()->maxLength(255),
                    TextInput::make('contact_phone')->tel()->maxLength(50),
                    Textarea::make('contact_address')->rows(2),
                    TextInput::make('contact_website')->url()->maxLength(255),
                    Textarea::make('footer_about')->rows(3),
                    TextInput::make('newsletter_title')->maxLength(255),
                ])->columns(2),
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
                                ->label('Save Settings')
                                ->submit('save')
                                ->keyBindings(['mod+s']),
                        ]),
                    ]),
            ]);
    }

    public function save(): void
    {
        try {
            $this->beginDatabaseTransaction();
            $data = $this->form->getState();
            SiteSetting::current()->update($data);
            $this->commitDatabaseTransaction();
            Notification::make()->title('Settings saved')->success()->send();
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
        return 'Site Settings';
    }
}
