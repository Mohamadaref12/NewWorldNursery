<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /** @var list<string> */
    private array $translatedColumns = [
        'site_name',
        'hero_eyebrow',
        'hero_title',
        'hero_subtitle',
        'hero_cta_primary',
        'hero_cta_secondary',
        'about_title',
        'about_content',
        'about_cta',
        'about_label',
        'about_highlight',
        'locations_label',
        'locations_title',
        'locations_title_highlight',
        'locations_subtitle',
        'programs_label',
        'programs_title',
        'programs_title_highlight',
        'programs_subtitle',
        'gallery_label',
        'gallery_title',
        'gallery_title_highlight',
        'gallery_subtitle',
        'gallery_cta',
        'moments_label',
        'moments_title',
        'moments_cta',
        'contact_label',
        'contact_title',
        'contact_title_highlight',
        'contact_subtitle',
        'contact_address',
        'footer_about',
        'newsletter_title',
    ];

    public function up(): void
    {
        Schema::create('site_setting_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('site_setting_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('site_name')->nullable();
            $table->string('hero_eyebrow')->nullable();
            $table->string('hero_title')->nullable();
            $table->text('hero_subtitle')->nullable();
            $table->string('hero_cta_primary')->nullable();
            $table->string('hero_cta_secondary')->nullable();
            $table->string('about_title')->nullable();
            $table->text('about_content')->nullable();
            $table->string('about_cta')->nullable();
            $table->string('about_label')->nullable();
            $table->string('about_highlight')->nullable();
            $table->string('locations_label')->nullable();
            $table->string('locations_title')->nullable();
            $table->string('locations_title_highlight')->nullable();
            $table->string('locations_subtitle')->nullable();
            $table->string('programs_label')->nullable();
            $table->string('programs_title')->nullable();
            $table->string('programs_title_highlight')->nullable();
            $table->string('programs_subtitle')->nullable();
            $table->string('gallery_label')->nullable();
            $table->string('gallery_title')->nullable();
            $table->string('gallery_title_highlight')->nullable();
            $table->string('gallery_subtitle')->nullable();
            $table->string('gallery_cta')->nullable();
            $table->string('moments_label')->nullable();
            $table->string('moments_title')->nullable();
            $table->string('moments_cta')->nullable();
            $table->string('contact_label')->nullable();
            $table->string('contact_title')->nullable();
            $table->string('contact_title_highlight')->nullable();
            $table->string('contact_subtitle')->nullable();
            $table->text('contact_address')->nullable();
            $table->text('footer_about')->nullable();
            $table->string('newsletter_title')->nullable();
            $table->unique(['site_setting_id', 'locale']);
        });

        $locale = config('app.locale', 'en');

        foreach (DB::table('site_settings')->get() as $row) {
            $payload = [
                'site_setting_id' => $row->id,
                'locale' => $locale,
            ];

            foreach ($this->translatedColumns as $column) {
                if (Schema::hasColumn('site_settings', $column)) {
                    $payload[$column] = $row->{$column} ?? null;
                }
            }

            DB::table('site_setting_translations')->insert($payload);
        }

        $drop = array_values(array_filter(
            $this->translatedColumns,
            fn (string $column) => Schema::hasColumn('site_settings', $column)
        ));

        if ($drop !== []) {
            Schema::table('site_settings', function (Blueprint $table) use ($drop) {
                $table->dropColumn($drop);
            });
        }
    }

    public function down(): void
    {
        foreach ($this->translatedColumns as $column) {
            if (Schema::hasColumn('site_settings', $column)) {
                continue;
            }

            Schema::table('site_settings', function (Blueprint $table) use ($column) {
                if (in_array($column, ['hero_subtitle', 'about_content', 'contact_address', 'footer_about'], true)) {
                    $table->text($column)->nullable();
                } else {
                    $table->string($column)->nullable();
                }
            });
        }

        $locale = config('app.locale', 'en');
        $fallback = config('app.fallback_locale', 'en');

        foreach (DB::table('site_settings')->pluck('id') as $id) {
            $translation = DB::table('site_setting_translations')
                ->where('site_setting_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if (! $translation) {
                continue;
            }

            $payload = [];
            foreach ($this->translatedColumns as $column) {
                $payload[$column] = $translation->{$column} ?? null;
            }

            DB::table('site_settings')->where('id', $id)->update($payload);
        }

        Schema::dropIfExists('site_setting_translations');
    }
};
