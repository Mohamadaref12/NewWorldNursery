<?php

namespace Database\Seeders;

use App\Models\GalleryCategory;
use App\Models\GalleryItem;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MomentsGallerySeeder extends Seeder
{
    /**
     * Seed gallery categories and Moments of Joy images.
     */
    public function run(): void
    {
        SiteSetting::current()->update([
            'moments_label' => 'GALLERY',
            'moments_title' => 'Moments of Joy',
            'moments_cta' => 'VIEW GALLERY',
        ]);

        $moments = $this->upsertCategory('moments-of-joy', 'Moments of Joy', 1);
        $classroom = $this->upsertCategory('classroom', 'Classroom', 2);

        GalleryItem::query()->delete();

        $disk = Storage::disk('images');
        $disk->makeDirectory('moments');

        $items = [
            ['category' => $moments, 'alt' => 'Colorful building blocks', 'colors' => [0xF5, 0xA6, 0x23]],
            ['category' => $moments, 'alt' => 'Child with face paint smiling', 'colors' => [0xE8, 0xA0, 0xB0]],
            ['category' => $classroom, 'alt' => 'Books, apple and ABC blocks', 'colors' => [0x2E, 0x9E, 0x94]],
            ['category' => $classroom, 'alt' => 'Teacher and child reading together', 'colors' => [0xB3, 0x9D, 0xDB]],
        ];

        foreach ($items as $index => $item) {
            $filename = 'moments/'.Str::ulid().'.jpg';
            $disk->put($filename, $this->makePlaceholderJpeg($item['colors'], $item['alt']));

            GalleryItem::query()->create([
                'gallery_category_id' => $item['category']->id,
                'image' => $filename,
                'alt' => $item['alt'],
                'sort_order' => $index + 1,
                'is_active' => true,
            ]);
        }

        $this->command?->info('Gallery seeded ('.GalleryItem::query()->count().' images, '.GalleryCategory::query()->count().' categories).');
    }

    private function upsertCategory(string $slug, string $name, int $sortOrder): GalleryCategory
    {
        $category = GalleryCategory::query()
            ->whereTranslation('slug', $slug)
            ->first();

        if ($category) {
            $category->fill([
                'name' => $name,
                'slug' => $slug,
                'sort_order' => $sortOrder,
                'is_active' => true,
            ])->save();

            return $category->refresh();
        }

        return GalleryCategory::query()->create([
            'name' => $name,
            'slug' => $slug,
            'sort_order' => $sortOrder,
            'is_active' => true,
        ]);
    }

    /**
     * @param  array{0: int, 1: int, 2: int}  $rgb
     */
    private function makePlaceholderJpeg(array $rgb, string $label): string
    {
        $width = 800;
        $height = 1000;
        $image = imagecreatetruecolor($width, $height);

        $background = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        imagefilledrectangle($image, 0, 0, $width, $height, $background);

        $white = imagecolorallocate($image, 255, 255, 255);
        $text = Str::limit($label, 28, '');
        imagestring($image, 5, 40, (int) ($height / 2), $text, $white);

        ob_start();
        imagejpeg($image, null, 85);
        $binary = (string) ob_get_clean();
        imagedestroy($image);

        return $binary;
    }
}
