<?php

namespace Database\Seeders;

use App\Models\Blog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        Blog::query()->delete();

        $posts = [
            [
                'title' => 'A Happy Start to Nursery Life',
                'excerpt' => 'How we help little ones settle into Al Barsha with calm routines, warm educators, and close parent partnership.',
                'content' => '<p>Starting nursery is a big step for every family. At New World Nursery in Al Barsha, we ease the transition with gentle settling sessions, familiar routines, and educators who take time to know each child.</p><p>Play-based learning, outdoor moments, and clear communication with parents help little ones feel safe — and grow with confidence from day one.</p>',
                'published_at' => now()->subDays(2),
            ],
            [
                'title' => 'Learning Through Play: Why It Matters',
                'excerpt' => 'Curiosity, creativity, and early skills grow best when children explore through play — here\'s how we do it each day.',
                'content' => '<p>Play is how young children make sense of the world. Our classrooms balance free play, guided activities, and early literacy and numeracy woven into everyday moments.</p><p>From sensory trays for toddlers to school-readiness projects for older children, every stage is matched to how children learn best.</p>',
                'published_at' => now()->subDays(7),
            ],
            [
                'title' => 'A Day in Our Toddler Room',
                'excerpt' => 'Soft routines, sensory play, and first friendships that ease the start of nursery life for our youngest learners.',
                'content' => '<p>Our toddler room is designed for first steps into group care: short circle times, lots of outdoor play, and space to rest when needed.</p><p>Educators focus on language, social confidence, and independence — always with warmth and patience.</p>',
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Preparing for Kindergarten with Confidence',
                'excerpt' => 'How our older programs build the skills and self-assurance children need for the next stage of learning.',
                'content' => '<p>As children move toward kindergarten, we gently introduce longer focus times, early writing and number skills, and collaborative projects.</p><p>The goal is not pressure — it is curiosity, resilience, and the confidence to take the next step.</p>',
                'published_at' => now()->subDays(21),
            ],
            [
                'title' => 'Partnering with Parents Every Step of the Way',
                'excerpt' => 'Open doors, clear updates, and shared goals — because the best nursery journeys are built together with families.',
                'content' => '<p>We believe parents are partners. Regular updates, open communication, and visits when you need them keep everyone aligned around your child\'s wellbeing and progress.</p><p>Whether you are booking a tour or settling into daily drop-off, our team is here to listen and support.</p>',
                'published_at' => now()->subDays(28),
            ],
        ];

        foreach ($posts as $index => $post) {
            Blog::query()->create([
                'title' => $post['title'],
                'slug' => Str::slug($post['title']),
                'excerpt' => $post['excerpt'],
                'content' => $post['content'],
                'image' => null,
                'published_at' => $post['published_at'],
                'is_active' => true,
            ]);
        }

        $this->command?->info('Blogs seeded ('.Blog::query()->count().' posts).');
    }
}
