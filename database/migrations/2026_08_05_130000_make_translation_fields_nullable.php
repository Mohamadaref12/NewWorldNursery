<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE feature_translations MODIFY title VARCHAR(255) NULL');
        DB::statement('ALTER TABLE blog_translations MODIFY title VARCHAR(255) NULL');
        DB::statement('ALTER TABLE blog_translations MODIFY slug VARCHAR(255) NULL');
        DB::statement('ALTER TABLE gallery_category_translations MODIFY name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE gallery_category_translations MODIFY slug VARCHAR(255) NULL');
        DB::statement('ALTER TABLE location_translations MODIFY name VARCHAR(255) NULL');
        DB::statement('ALTER TABLE location_translations MODIFY city VARCHAR(255) NULL');
        DB::statement('ALTER TABLE program_translations MODIFY title VARCHAR(255) NULL');
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() !== 'mysql') {
            return;
        }

        DB::statement('ALTER TABLE feature_translations MODIFY title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE blog_translations MODIFY title VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE blog_translations MODIFY slug VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE gallery_category_translations MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE gallery_category_translations MODIFY slug VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE location_translations MODIFY name VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE location_translations MODIFY city VARCHAR(255) NOT NULL');
        DB::statement('ALTER TABLE program_translations MODIFY title VARCHAR(255) NOT NULL');
    }
};
