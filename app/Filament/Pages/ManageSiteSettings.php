<?php

namespace App\Filament\Pages;

use App\Filament\Forms\LocaleTabs;
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
use UnitEnum;

/**
 * @property-read Schema $form
 */
class ManageSiteSettings extends Page
{
    use CanUseDatabaseTransactions;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Site Settings';

    protected static string|UnitEnum|null $navigationGroup = 'Website';

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
        $settings = SiteSetting::current();
        $settings->loadMissing('translations');

        $data = $settings->attributesToArray();

        foreach (['en', 'ar'] as $locale) {
            $translation = $settings->translation($locale);

            foreach ($settings->getTranslatedAttributes() as $attribute) {
                $data[$locale][$attribute] = $translation?->getAttribute($attribute);
            }
        }

        foreach ($settings->getTranslatedAttributes() as $attribute) {
            unset($data[$attribute]);
        }

        $this->form->fill($data);
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
                LocaleTabs::make(
                    $this->translationSections('en'),
                    $this->translationSections('ar', rtl: true),
                ),
                Section::make('Contact details & media')->schema([
                    TextInput::make('top_bar_phone')->tel()->maxLength(50),
                    TextInput::make('top_bar_email')->email()->maxLength(255),
                    TextInput::make('contact_email')->email()->maxLength(255),
                    TextInput::make('contact_phone')->tel()->maxLength(50),
                    TextInput::make('contact_website')->url()->maxLength(255),
                    FileUpload::make('hero_image')->image()->directory('site')->disk('images')->visibility('public'),
                    FileUpload::make('about_image')->image()->directory('site')->disk('images')->visibility('public'),
                ])->columns(2)->columnSpanFull(),
                Section::make('Social Media')->schema([
                    TextInput::make('facebook_url')->url()->maxLength(255),
                    TextInput::make('instagram_url')->url()->maxLength(255),
                    TextInput::make('twitter_url')->url()->maxLength(255),
                    TextInput::make('youtube_url')->url()->maxLength(255),
                ])->columns(2)->columnSpanFull(),
            ]);
    }

    /**
     * @return array<int, mixed>
     */
    protected function translationSections(string $locale, bool $rtl = false): array
    {
        $attrs = $rtl ? ['dir' => 'rtl'] : [];
        $input = fn (TextInput $field): TextInput => $rtl ? $field->extraInputAttributes($attrs) : $field;
        $area = fn (Textarea $field): Textarea => $rtl ? $field->extraInputAttributes($attrs) : $field;

        return [
            Section::make($rtl ? 'عام' : 'General')->schema([
                $input(TextInput::make("{$locale}.site_name")->label($rtl ? 'اسم الموقع' : 'Site name')->required()->maxLength(255)),
            ]),
            Section::make($rtl ? 'الهيرو' : 'Hero Section')->schema([
                $input(TextInput::make("{$locale}.hero_eyebrow")->label($rtl ? 'العنوان الفرعي العلوي' : 'Eyebrow')->maxLength(255)),
                $input(TextInput::make("{$locale}.hero_title")->label($rtl ? 'العنوان' : 'Title')->maxLength(255)),
                $area(Textarea::make("{$locale}.hero_subtitle")->label($rtl ? 'الوصف' : 'Subtitle')->rows(3)),
                $input(TextInput::make("{$locale}.hero_cta_primary")->label($rtl ? 'الزر الأساسي' : 'Primary CTA')->maxLength(100)),
                $input(TextInput::make("{$locale}.hero_cta_secondary")->label($rtl ? 'الزر الثانوي' : 'Secondary CTA')->maxLength(100)),
            ])->columns(2),
            Section::make($rtl ? 'من نحن' : 'About Section')->schema([
                $input(TextInput::make("{$locale}.about_label")->label($rtl ? 'التصنيف' : 'Label')->maxLength(100)),
                $input(TextInput::make("{$locale}.about_title")->label($rtl ? 'العنوان' : 'Title')->maxLength(255)),
                $input(TextInput::make("{$locale}.about_highlight")->label($rtl ? 'التظليل' : 'Highlight')->maxLength(255)),
                $area(Textarea::make("{$locale}.about_content")->label($rtl ? 'المحتوى' : 'Content')->rows(5)),
                $input(TextInput::make("{$locale}.about_cta")->label($rtl ? 'الزر' : 'CTA')->maxLength(100)),
            ])->columns(2),
            Section::make($rtl ? 'عناوين الأقسام' : 'Section Headings')->schema([
                $input(TextInput::make("{$locale}.locations_label")->label($rtl ? 'تصنيف المواقع' : 'Locations label')->maxLength(100)),
                $input(TextInput::make("{$locale}.locations_title")->label($rtl ? 'عنوان المواقع' : 'Locations title')->maxLength(255)),
                $input(TextInput::make("{$locale}.locations_title_highlight")->label($rtl ? 'تظليل المواقع' : 'Locations highlight')->maxLength(100)),
                $input(TextInput::make("{$locale}.locations_subtitle")->label($rtl ? 'وصف المواقع' : 'Locations subtitle')->maxLength(500)),
                $input(TextInput::make("{$locale}.programs_label")->label($rtl ? 'تصنيف البرامج' : 'Programs label')->maxLength(100)),
                $input(TextInput::make("{$locale}.programs_title")->label($rtl ? 'عنوان البرامج' : 'Programs title')->maxLength(255)),
                $input(TextInput::make("{$locale}.programs_title_highlight")->label($rtl ? 'تظليل البرامج' : 'Programs highlight')->maxLength(100)),
                $input(TextInput::make("{$locale}.programs_subtitle")->label($rtl ? 'وصف البرامج' : 'Programs subtitle')->maxLength(500)),
                $input(TextInput::make("{$locale}.gallery_label")->label($rtl ? 'تصنيف إنستغرام' : 'Instagram label')->maxLength(100)),
                $input(TextInput::make("{$locale}.gallery_title")->label($rtl ? 'عنوان إنستغرام' : 'Instagram title')->maxLength(255)),
                $input(TextInput::make("{$locale}.gallery_title_highlight")->label($rtl ? 'تظليل إنستغرام' : 'Instagram highlight')->maxLength(100)),
                $input(TextInput::make("{$locale}.gallery_subtitle")->label($rtl ? 'وصف إنستغرام' : 'Instagram subtitle')->maxLength(500)),
                $input(TextInput::make("{$locale}.gallery_cta")->label($rtl ? 'زر إنستغرام' : 'Instagram CTA')->maxLength(100)),
                $input(TextInput::make("{$locale}.moments_label")->label($rtl ? 'تصنيف المعرض' : 'Moments label')->maxLength(100)),
                $input(TextInput::make("{$locale}.moments_title")->label($rtl ? 'عنوان المعرض' : 'Moments title')->maxLength(255)),
                $input(TextInput::make("{$locale}.moments_cta")->label($rtl ? 'زر المعرض' : 'Moments CTA')->maxLength(100)),
            ])->columns(2),
            Section::make($rtl ? 'التواصل والتذييل' : 'Contact & Footer')->schema([
                $input(TextInput::make("{$locale}.contact_label")->label($rtl ? 'التصنيف' : 'Label')->maxLength(100)),
                $input(TextInput::make("{$locale}.contact_title")->label($rtl ? 'العنوان' : 'Title')->maxLength(255)),
                $input(TextInput::make("{$locale}.contact_title_highlight")->label($rtl ? 'التظليل' : 'Highlight')->maxLength(100)),
                $input(TextInput::make("{$locale}.contact_subtitle")->label($rtl ? 'الوصف' : 'Subtitle')->maxLength(500)),
                $area(Textarea::make("{$locale}.contact_address")->label($rtl ? 'العنوان' : 'Address')->rows(2)),
                $area(Textarea::make("{$locale}.footer_about")->label($rtl ? 'نبذة التذييل' : 'Footer about')->rows(3)),
                $input(TextInput::make("{$locale}.newsletter_title")->label($rtl ? 'عنوان النشرة' : 'Newsletter title')->maxLength(255)),
            ])->columns(2),
        ];
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
