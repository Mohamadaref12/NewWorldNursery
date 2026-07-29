<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gallery_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('instagram_posts', function (Blueprint $table) {
            $table->id();
            $table->string('instagram_media_id')->unique();
            $table->string('image');
            $table->string('alt')->nullable();
            $table->string('permalink')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        $defaultCategoryId = DB::table('gallery_categories')->insertGetId([
            'name' => 'Moments of Joy',
            'slug' => 'moments-of-joy',
            'sort_order' => 1,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (! Schema::hasTable('gallery_items')) {
            return;
        }

        if (Schema::hasColumn('gallery_items', 'type')) {
            $instagramRows = DB::table('gallery_items')
                ->where('type', 'instagram')
                ->get();

            foreach ($instagramRows as $row) {
                if (blank($row->instagram_media_id ?? null)) {
                    continue;
                }

                DB::table('instagram_posts')->updateOrInsert(
                    ['instagram_media_id' => $row->instagram_media_id],
                    [
                        'image' => $row->image,
                        'alt' => $row->alt,
                        'permalink' => $row->permalink,
                        'sort_order' => $row->sort_order,
                        'is_active' => $row->is_active,
                        'created_at' => $row->created_at ?? now(),
                        'updated_at' => $row->updated_at ?? now(),
                    ]
                );
            }

            DB::table('gallery_items')->where('type', 'instagram')->delete();
        }

        if (! Schema::hasColumn('gallery_items', 'gallery_category_id')) {
            Schema::table('gallery_items', function (Blueprint $table) {
                $table->foreignId('gallery_category_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('gallery_categories')
                    ->nullOnDelete();
            });
        }

        DB::table('gallery_items')
            ->whereNull('gallery_category_id')
            ->update(['gallery_category_id' => $defaultCategoryId]);

        $drop = array_values(array_filter([
            Schema::hasColumn('gallery_items', 'type') ? 'type' : null,
            Schema::hasColumn('gallery_items', 'instagram_media_id') ? 'instagram_media_id' : null,
            Schema::hasColumn('gallery_items', 'permalink') ? 'permalink' : null,
        ]));

        if ($drop !== []) {
            Schema::table('gallery_items', function (Blueprint $table) use ($drop) {
                $table->dropColumn($drop);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('gallery_items') && ! Schema::hasColumn('gallery_items', 'type')) {
            Schema::table('gallery_items', function (Blueprint $table) {
                $table->string('type', 20)->default('moments')->after('id');
                $table->string('instagram_media_id')->nullable()->after('type');
                $table->string('permalink')->nullable()->after('alt');
            });

            foreach (DB::table('instagram_posts')->get() as $post) {
                DB::table('gallery_items')->insert([
                    'type' => 'instagram',
                    'instagram_media_id' => $post->instagram_media_id,
                    'image' => $post->image,
                    'alt' => $post->alt,
                    'permalink' => $post->permalink,
                    'sort_order' => $post->sort_order,
                    'is_active' => $post->is_active,
                    'created_at' => $post->created_at,
                    'updated_at' => $post->updated_at,
                ]);
            }

            DB::table('gallery_items')
                ->where(function ($query) {
                    $query->whereNull('type')->orWhere('type', '!=', 'instagram');
                })
                ->update(['type' => 'moments']);

            if (Schema::hasColumn('gallery_items', 'gallery_category_id')) {
                Schema::table('gallery_items', function (Blueprint $table) {
                    $table->dropConstrainedForeignId('gallery_category_id');
                });
            }
        }

        Schema::dropIfExists('instagram_posts');
        Schema::dropIfExists('gallery_categories');
    }
};
