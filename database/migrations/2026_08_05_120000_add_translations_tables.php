<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('feature_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('feature_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->text('description')->nullable();
            $table->unique(['feature_id', 'locale']);
        });

        Schema::create('blog_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->string('slug');
            $table->string('excerpt')->nullable();
            $table->longText('content')->nullable();
            $table->unique(['blog_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        Schema::create('gallery_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_category_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name');
            $table->string('slug');
            $table->unique(['gallery_category_id', 'locale']);
            $table->unique(['locale', 'slug']);
        });

        Schema::create('gallery_item_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_item_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('alt')->nullable();
            $table->unique(['gallery_item_id', 'locale']);
        });

        Schema::create('location_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('location_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('name');
            $table->string('city');
            $table->string('country')->nullable();
            $table->text('address')->nullable();
            $table->string('working_hours')->nullable();
            $table->unique(['location_id', 'locale']);
        });

        Schema::create('program_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('title');
            $table->string('age_range')->nullable();
            $table->text('description')->nullable();
            $table->unique(['program_id', 'locale']);
        });

        Schema::create('instagram_post_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('instagram_post_id')->constrained()->cascadeOnDelete();
            $table->string('locale', 5);
            $table->string('alt')->nullable();
            $table->unique(['instagram_post_id', 'locale']);
        });

        $this->migrateExistingData();
        $this->dropTranslatedColumns();
    }

    public function down(): void
    {
        $this->restoreTranslatedColumns();
        $this->restoreDataFromTranslations();

        Schema::dropIfExists('instagram_post_translations');
        Schema::dropIfExists('program_translations');
        Schema::dropIfExists('location_translations');
        Schema::dropIfExists('gallery_item_translations');
        Schema::dropIfExists('gallery_category_translations');
        Schema::dropIfExists('blog_translations');
        Schema::dropIfExists('feature_translations');
    }

    private function migrateExistingData(): void
    {
        $locale = config('app.locale', 'en');

        foreach (DB::table('features')->get() as $row) {
            DB::table('feature_translations')->insert([
                'feature_id' => $row->id,
                'locale' => $locale,
                'title' => $row->title,
                'description' => $row->description ?? null,
            ]);
        }

        foreach (DB::table('blogs')->get() as $row) {
            DB::table('blog_translations')->insert([
                'blog_id' => $row->id,
                'locale' => $locale,
                'title' => $row->title,
                'slug' => $row->slug,
                'excerpt' => $row->excerpt ?? null,
                'content' => $row->content ?? null,
            ]);
        }

        foreach (DB::table('gallery_categories')->get() as $row) {
            DB::table('gallery_category_translations')->insert([
                'gallery_category_id' => $row->id,
                'locale' => $locale,
                'name' => $row->name,
                'slug' => $row->slug,
            ]);
        }

        if (Schema::hasTable('gallery_items')) {
            foreach (DB::table('gallery_items')->get() as $row) {
                DB::table('gallery_item_translations')->insert([
                    'gallery_item_id' => $row->id,
                    'locale' => $locale,
                    'alt' => $row->alt ?? null,
                ]);
            }
        }

        foreach (DB::table('locations')->get() as $row) {
            DB::table('location_translations')->insert([
                'location_id' => $row->id,
                'locale' => $locale,
                'name' => $row->name,
                'city' => $row->city,
                'country' => $row->country ?? null,
                'address' => $row->address ?? null,
                'working_hours' => $row->working_hours ?? null,
            ]);
        }

        foreach (DB::table('programs')->get() as $row) {
            DB::table('program_translations')->insert([
                'program_id' => $row->id,
                'locale' => $locale,
                'title' => $row->title,
                'age_range' => $row->age_range ?? null,
                'description' => $row->description ?? null,
            ]);
        }

        if (Schema::hasTable('instagram_posts')) {
            foreach (DB::table('instagram_posts')->get() as $row) {
                DB::table('instagram_post_translations')->insert([
                    'instagram_post_id' => $row->id,
                    'locale' => $locale,
                    'alt' => $row->alt ?? null,
                ]);
            }
        }
    }

    private function dropTranslatedColumns(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->dropColumn(['title', 'description']);
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['title', 'slug', 'excerpt', 'content']);
        });

        Schema::table('gallery_categories', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn(['name', 'slug']);
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->dropColumn(['alt']);
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->dropColumn(['name', 'city', 'country', 'address', 'working_hours']);
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn(['title', 'age_range', 'description']);
        });

        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->dropColumn(['alt']);
        });
    }

    private function restoreTranslatedColumns(): void
    {
        Schema::table('features', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->text('description')->nullable()->after('title');
        });

        Schema::table('blogs', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('slug')->unique()->after('title');
            $table->string('excerpt')->nullable()->after('slug');
            $table->longText('content')->nullable()->after('excerpt');
        });

        Schema::table('gallery_categories', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('slug')->unique()->after('name');
        });

        Schema::table('gallery_items', function (Blueprint $table) {
            $table->string('alt')->nullable()->after('image');
        });

        Schema::table('locations', function (Blueprint $table) {
            $table->string('name')->after('id');
            $table->string('city')->after('name');
            $table->string('country')->nullable()->after('city');
            $table->text('address')->nullable()->after('badge_color');
            $table->string('working_hours')->nullable()->after('email');
        });

        Schema::table('programs', function (Blueprint $table) {
            $table->string('title')->after('id');
            $table->string('age_range')->nullable()->after('title');
            $table->text('description')->nullable()->after('age_range');
        });

        Schema::table('instagram_posts', function (Blueprint $table) {
            $table->string('alt')->nullable()->after('image');
        });
    }

    private function restoreDataFromTranslations(): void
    {
        $locale = config('app.locale', 'en');
        $fallback = config('app.fallback_locale', 'en');

        foreach (DB::table('features')->pluck('id') as $id) {
            $t = DB::table('feature_translations')
                ->where('feature_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if ($t) {
                DB::table('features')->where('id', $id)->update([
                    'title' => $t->title,
                    'description' => $t->description,
                ]);
            }
        }

        foreach (DB::table('blogs')->pluck('id') as $id) {
            $t = DB::table('blog_translations')
                ->where('blog_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if ($t) {
                DB::table('blogs')->where('id', $id)->update([
                    'title' => $t->title,
                    'slug' => $t->slug,
                    'excerpt' => $t->excerpt,
                    'content' => $t->content,
                ]);
            }
        }

        foreach (DB::table('gallery_categories')->pluck('id') as $id) {
            $t = DB::table('gallery_category_translations')
                ->where('gallery_category_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if ($t) {
                DB::table('gallery_categories')->where('id', $id)->update([
                    'name' => $t->name,
                    'slug' => $t->slug,
                ]);
            }
        }

        foreach (DB::table('gallery_items')->pluck('id') as $id) {
            $t = DB::table('gallery_item_translations')
                ->where('gallery_item_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if ($t) {
                DB::table('gallery_items')->where('id', $id)->update([
                    'alt' => $t->alt,
                ]);
            }
        }

        foreach (DB::table('locations')->pluck('id') as $id) {
            $t = DB::table('location_translations')
                ->where('location_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if ($t) {
                DB::table('locations')->where('id', $id)->update([
                    'name' => $t->name,
                    'city' => $t->city,
                    'country' => $t->country,
                    'address' => $t->address,
                    'working_hours' => $t->working_hours,
                ]);
            }
        }

        foreach (DB::table('programs')->pluck('id') as $id) {
            $t = DB::table('program_translations')
                ->where('program_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if ($t) {
                DB::table('programs')->where('id', $id)->update([
                    'title' => $t->title,
                    'age_range' => $t->age_range,
                    'description' => $t->description,
                ]);
            }
        }

        foreach (DB::table('instagram_posts')->pluck('id') as $id) {
            $t = DB::table('instagram_post_translations')
                ->where('instagram_post_id', $id)
                ->whereIn('locale', [$locale, $fallback])
                ->orderByRaw('locale = ? desc', [$locale])
                ->first();

            if ($t) {
                DB::table('instagram_posts')->where('id', $id)->update([
                    'alt' => $t->alt,
                ]);
            }
        }
    }
};
