<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\GalleryItem;
use App\Models\Location;
use App\Models\Program;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;

class NurserySeeder extends Seeder
{
    /**
     * Seed site content exactly as provided in the New World Nursery design.
     */
    public function run(): void
    {
        SiteSetting::query()->updateOrCreate(['id' => 1], [
            'site_name' => 'New World Nursery',
            'top_bar_phone' => '+971 50 123 4567',
            'top_bar_email' => 'info@newworldnursery.ae',
            'facebook_url' => 'https://facebook.com',
            'instagram_url' => 'https://instagram.com',
            'twitter_url' => null,
            'youtube_url' => 'https://youtube.com',

            // Hero
            'hero_eyebrow' => 'NEW WORLD NURSERY - DUBAI',
            'hero_title' => 'A Happy Place to Learn & Grow',
            'hero_subtitle' => 'A warm Dubai nursery where play-based learning, caring educators, and close parent partnership help little ones explore and grow with confidence.',
            'hero_cta_primary' => 'ENQUIRE NOW',
            'hero_cta_secondary' => 'OUR PROGRAMS',

            // About
            'about_label' => 'ABOUT US',
            'about_title' => 'Welcome to New World Nursery',
            'about_highlight' => 'New World Nursery',
            'about_content' => 'Based in Dubai, we welcome children into bright classrooms and thoughtful routines built around curiosity, creativity, and care. From toddlers to kindergarten, every day balances play, early skills, and the confidence to take the next step.',
            'about_cta' => 'BOOK A VISIT',

            // Locations section
            'locations_label' => 'OUR LOCATIONS',
            'locations_title' => 'Find us across',
            'locations_title_highlight' => 'the region',
            'locations_subtitle' => 'Start with our Dubai home in Al Barsha — then explore sister nurseries welcoming families across the Gulf.',

            // Programs section
            'programs_label' => 'OUR PROGRAMS',
            'programs_title' => 'Learning by',
            'programs_title_highlight' => 'age & stage',
            'programs_subtitle' => 'Play-led pathways from first steps to school readiness — each stage matched to how children learn best.',

            // Instagram feed section
            'gallery_label' => 'INSTAGRAM',
            'gallery_title' => 'Follow',
            'gallery_title_highlight' => 'Our Journey',
            'gallery_subtitle' => 'Peek into classroom moments, outdoor play, and the everyday joy of New World Nursery life.',
            'gallery_cta' => 'FOLLOW US ON INSTAGRAM',

            // Moments of Joy gallery section
            'moments_label' => 'GALLERY',
            'moments_title' => 'Moments of Joy',
            'moments_cta' => 'VIEW GALLERY',

            // Contact
            'contact_label' => 'PLAN A VISIT',
            'contact_title' => 'Talk with',
            'contact_title_highlight' => 'Our Team',
            'contact_subtitle' => "Tell us your child's age and preferred program — we'll help you book a tour of our Al Barsha nursery and answer enrolment questions.",
            'contact_email' => 'info@newworldnursery.ae',
            'contact_phone' => '+971 50 123 4567',
            'contact_address' => 'Al Barsha, Dubai, UAE',
            'contact_website' => 'https://newworldnursery.ae',

            // Footer / newsletter
            'footer_about' => 'New World Nursery provides a warm Dubai nursery where play-based learning, caring educators, and close parent partnership help little ones explore and grow with confidence.',
            'newsletter_title' => 'Come see New World in action',
        ]);

        Feature::query()->delete();
        foreach ([
            [
                'title' => 'Safe & Secure',
                'description' => 'Supervised spaces and clear routines so families feel at ease every day.',
                'icon_color' => '#D4EDDA',
                'sort_order' => 1,
            ],
            [
                'title' => 'Caring Educators',
                'description' => 'Warm, experienced teachers who know each child by name and pace.',
                'icon_color' => '#FDE8D8',
                'sort_order' => 2,
            ],
            [
                'title' => 'Play-based Learning',
                'description' => 'Hands-on play that builds language, curiosity, and early skills.',
                'icon_color' => '#FFF3CD',
                'sort_order' => 3,
            ],
            [
                'title' => 'Whole-Child Growth',
                'description' => 'Social, emotional, cognitive, and physical development in balance.',
                'icon_color' => '#E8DAEF',
                'sort_order' => 4,
            ],
            [
                'title' => 'Parent Partnership',
                'description' => 'Open updates and shared goals so home and nursery stay aligned.',
                'icon_color' => '#D6EAF8',
                'sort_order' => 5,
            ],
        ] as $feature) {
            Feature::query()->create($feature + ['is_active' => true]);
        }

        Location::query()->delete();
        foreach ([
            [
                'name' => 'New World Nursery - Dubai',
                'city' => 'Dubai',
                'country' => 'United Arab Emirates',
                'badge_color' => '#2E9E94',
                'address' => 'Al Barsha, Dubai, UAE',
                'phone' => '+971 50 123 4567',
                'email' => 'dubai@newworldnursery.ae',
                'working_hours' => 'Sun – Thu: 7:00 AM – 6:00 PM',
                'map_url' => 'https://maps.google.com',
                'visit_url' => '#contact',
                'sort_order' => 1,
            ],
            [
                'name' => 'New World Nursery - Riyadh',
                'city' => 'Riyadh',
                'country' => 'Saudi Arabia',
                'badge_color' => '#E8A0B0',
                'address' => 'Al Olaya District, Riyadh',
                'phone' => '+966 50 123 4567',
                'email' => 'riyadh@newworldnursery.ae',
                'working_hours' => 'Sun – Thu: 7:00 AM – 6:00 PM',
                'map_url' => 'https://maps.google.com',
                'visit_url' => '#contact',
                'sort_order' => 2,
            ],
            [
                'name' => 'New World Nursery - Doha',
                'city' => 'Doha',
                'country' => 'Qatar',
                'badge_color' => '#F5D76E',
                'address' => 'West Bay, Doha',
                'phone' => '+974 50 123 4567',
                'email' => 'doha@newworldnursery.ae',
                'working_hours' => 'Sun – Thu: 7:00 AM – 6:00 PM',
                'map_url' => 'https://maps.google.com',
                'visit_url' => '#contact',
                'sort_order' => 3,
            ],
            [
                'name' => 'New World Nursery - Kuwait City',
                'city' => 'Kuwait City',
                'country' => 'Kuwait',
                'badge_color' => '#B39DDB',
                'address' => 'Salmiya, Kuwait City',
                'phone' => '+965 50 123 4567',
                'email' => 'kuwait@newworldnursery.ae',
                'working_hours' => 'Sun – Thu: 7:00 AM – 6:00 PM',
                'map_url' => 'https://maps.google.com',
                'visit_url' => '#contact',
                'sort_order' => 4,
            ],
        ] as $location) {
            Location::query()->create($location + ['is_active' => true]);
        }

        Program::query()->delete();
        foreach ([
            [
                'title' => 'Toddlers',
                'age_range' => '18 Months - 2.5 Years',
                'description' => 'Soft routines, sensory play, and first friendships that ease the start of nursery life.',
                'color' => '#E8F5E9',
                'icon' => '👶',
                'icon_color' => '#81C784',
                'sort_order' => 1,
            ],
            [
                'title' => 'Nursery',
                'age_range' => '2.5 - 3.5 Years',
                'description' => 'Growing independence through language, sharing, and confident everyday skills.',
                'color' => '#FCE4EC',
                'icon' => '🧱',
                'icon_color' => '#F48FB1',
                'sort_order' => 2,
            ],
            [
                'title' => 'Pre-Nursery',
                'age_range' => '3.5 - 4.5 Years',
                'description' => 'Curiosity-led projects that stretch thinking, creativity, and social confidence.',
                'color' => '#FFF8E1',
                'icon' => '💡',
                'icon_color' => '#FFB74D',
                'sort_order' => 3,
            ],
            [
                'title' => 'KG',
                'age_range' => '4.5 - 5.5 Years',
                'description' => 'School-ready focus on literacy, numeracy, and the social skills for a smooth move up.',
                'color' => '#E0F7FA',
                'icon' => '🎓',
                'icon_color' => '#4DB6AC',
                'sort_order' => 4,
            ],
        ] as $program) {
            Program::query()->create($program + ['is_active' => true]);
        }

        // Instagram feed images stay empty (upload from Filament, type: Instagram).
        // Moments of Joy images are seeded by MomentsGallerySeeder.
        GalleryItem::query()->instagram()->delete();
    }
}
