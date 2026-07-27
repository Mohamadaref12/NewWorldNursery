@php
    use App\Support\ImageUrl;

    $imageUrl = fn (?string $path) => ImageUrl::make($path);

    $titleWithHighlight = function (?string $title, ?string $highlight): string {
        $title = e($title ?? '');
        $highlight = trim((string) $highlight);

        if ($highlight === '' || ! str_contains($title, e($highlight))) {
            return $title;
        }

        return str_replace(e($highlight), '<span class="highlight-brush">'.e($highlight).'</span>', $title);
    };
@endphp

@extends('layouts.app')

@section('content')
    {{-- Top bar --}}
    <div class="bg-nursery-teal text-white text-sm">
        <div class="max-w-7xl mx-auto px-4 py-2 flex flex-wrap items-center justify-between gap-2">
            <div class="flex flex-wrap items-center gap-4">
                @if ($settings->top_bar_phone)
                    <a href="tel:{{ $settings->top_bar_phone }}" class="hover:opacity-80">{{ $settings->top_bar_phone }}</a>
                @endif
                @if ($settings->top_bar_email)
                    <a href="mailto:{{ $settings->top_bar_email }}" class="hover:opacity-80">{{ $settings->top_bar_email }}</a>
                @endif
            </div>
            <div class="flex items-center gap-3">
                @foreach (['facebook_url' => 'Fb', 'instagram_url' => 'Ig', 'twitter_url' => 'X', 'youtube_url' => 'Yt'] as $field => $label)
                    @if ($settings->{$field})
                        <a href="{{ $settings->{$field} }}" target="_blank" rel="noopener" class="hover:opacity-80">{{ $label }}</a>
                    @endif
                @endforeach
            </div>
        </div>
    </div>

    {{-- Navbar --}}
    <header class="bg-white/95 backdrop-blur sticky top-0 z-50 border-b border-gray-100">
        <div class="max-w-7xl mx-auto px-4 py-4 flex items-center justify-between gap-4">
            <a href="{{ route('home') }}" class="flex items-center gap-2 min-w-0">
                <span class="text-2xl shrink-0">🌈</span>
                <span class="font-extrabold text-nursery-teal truncate">{{ $settings->site_name }}</span>
            </a>
            <nav class="hidden lg:flex items-center gap-6 text-sm font-semibold text-gray-600">
                <a href="#home" class="hover:text-nursery-teal">Home</a>
                <a href="#about" class="hover:text-nursery-teal">About Us</a>
                <a href="#locations" class="hover:text-nursery-teal">Our Nursery</a>
                <a href="#programs" class="hover:text-nursery-teal">Programs</a>
                <a href="#contact" class="hover:text-nursery-teal">Contact Us</a>
            </nav>
            <a href="#contact" class="bg-nursery-teal hover:bg-nursery-teal-dark text-white text-xs sm:text-sm font-bold px-5 py-2.5 rounded-full transition shrink-0">
                ENQUIRE NOW
            </a>
        </div>
    </header>

    {{-- Hero --}}
    <section id="home" class="relative overflow-hidden bg-white">
        <div class="absolute -left-20 bottom-0 w-80 h-40 bg-nursery-green/40 rounded-[100%] blur-2xl pointer-events-none"></div>
        <div class="max-w-7xl mx-auto px-4 py-14 lg:py-20 grid lg:grid-cols-2 gap-10 lg:gap-16 items-center">
            <div class="relative z-10">
                @if ($settings->hero_eyebrow)
                    <p class="text-xs sm:text-sm font-bold tracking-[0.18em] uppercase text-gray-500 mb-4">{{ $settings->hero_eyebrow }}</p>
                @endif
                <h1 class="text-4xl sm:text-5xl lg:text-[3.4rem] font-extrabold leading-[1.15] mb-5">
                    @php
                        $words = preg_split('/\s+/', trim($settings->hero_title ?? 'A Happy Place to Learn & Grow')) ?: [];
                        $palette = ['text-emerald-500', 'text-amber-400', 'text-rose-400', 'text-nursery-teal', 'text-nursery-navy', 'text-sky-500'];
                    @endphp
                    @foreach ($words as $i => $word)
                        <span class="{{ $palette[$i % count($palette)] }}">{{ $word }}</span>{{ $i < count($words) - 1 ? ' ' : '' }}
                    @endforeach
                </h1>
                <p class="text-gray-600 text-base sm:text-lg leading-relaxed mb-8 max-w-xl">{{ $settings->hero_subtitle }}</p>
                <div class="flex flex-wrap gap-3">
                    <a href="#contact" class="bg-nursery-teal hover:bg-nursery-teal-dark text-white font-bold px-7 py-3 rounded-full transition shadow-sm">
                        {{ $settings->hero_cta_primary }}
                    </a>
                    <a href="#programs" class="border-2 border-nursery-teal text-nursery-teal hover:bg-nursery-teal hover:text-white font-bold px-7 py-3 rounded-full transition">
                        {{ $settings->hero_cta_secondary }}
                    </a>
                </div>
                <div class="mt-8 flex gap-3 text-2xl opacity-80" aria-hidden="true">
                    <span>☀️</span><span>🍃</span><span>💕</span>
                </div>
            </div>
            <div class="relative">
                <div class="absolute -top-4 right-8 text-4xl z-10" aria-hidden="true">☀️</div>
                <div class="absolute -bottom-2 right-4 text-4xl z-10" aria-hidden="true">🌈</div>
                @if ($settings->hero_image)
                    <img src="{{ $imageUrl($settings->hero_image) }}" alt="Children learning" class="cloud-mask w-full aspect-[4/3] object-cover shadow-xl">
                @else
                    <div class="cloud-mask w-full aspect-[4/3] bg-gradient-to-br from-nursery-yellow via-nursery-pink to-nursery-blue flex items-center justify-center text-7xl shadow-xl">🧒👧</div>
                @endif
            </div>
        </div>
    </section>

    {{-- Features --}}
    @if ($features->isNotEmpty())
        <section class="bg-nursery-cream/60 py-12 border-y border-gray-100">
            <div class="max-w-7xl mx-auto px-4 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-8 lg:gap-4">
                @foreach ($features as $index => $feature)
                    <div class="text-center px-3 {{ $index < $features->count() - 1 ? 'lg:border-r lg:border-gray-200/80' : '' }}">
                        <div class="w-16 h-16 mx-auto mb-4 rounded-full flex items-center justify-center text-2xl overflow-hidden" style="background-color: {{ $feature->icon_color }}">
                            @if ($feature->icon_image)
                                <img src="{{ $imageUrl($feature->icon_image) }}" alt="" class="w-10 h-10 object-contain">
                            @endif
                        </div>
                        <h3 class="font-extrabold text-nursery-navy mb-2">{{ $feature->title }}</h3>
                        @if ($feature->description)
                            <p class="text-sm text-gray-500 leading-relaxed">{{ $feature->description }}</p>
                        @endif
                    </div>
                @endforeach
            </div>
        </section>
    @endif

    {{-- About --}}
    <section id="about" class="py-20 bg-white relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-center">
            <div class="relative">
                <div class="absolute -bottom-3 -left-3 w-28 h-28 border-[6px] border-nursery-pink rounded-bl-[3rem] rounded-tr-3xl pointer-events-none"></div>
                @if ($settings->about_image)
                    <img src="{{ $imageUrl($settings->about_image) }}" alt="About" class="relative rounded-[2rem] w-full aspect-square object-cover shadow-lg">
                @else
                    <div class="relative rounded-[2rem] w-full aspect-square bg-gradient-to-br from-nursery-pink to-nursery-yellow flex items-center justify-center text-7xl shadow-lg">📷</div>
                @endif
                <div class="absolute -bottom-6 left-6 text-5xl" aria-hidden="true">🎓</div>
            </div>
            <div>
                @if ($settings->about_label)
                    <p class="text-nursery-teal font-bold text-sm tracking-[0.2em] uppercase mb-3">{{ $settings->about_label }}</p>
                @endif
                <h2 class="text-3xl lg:text-4xl font-extrabold text-nursery-navy mb-6 leading-snug">
                    {!! $titleWithHighlight($settings->about_title, $settings->about_highlight) !!}
                </h2>
                <p class="text-gray-600 leading-relaxed mb-8 whitespace-pre-line">{{ $settings->about_content }}</p>
                <a href="#contact" class="inline-block border-2 border-nursery-pink text-nursery-pink hover:bg-nursery-pink hover:text-white font-bold px-7 py-3 rounded-full transition">
                    {{ $settings->about_cta }}
                </a>
            </div>
        </div>
    </section>

    {{-- Locations --}}
    <section id="locations" class="py-20 bg-nursery-cream/50">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-12">
                @if ($settings->locations_label)
                    <p class="text-nursery-teal font-bold text-sm tracking-[0.2em] uppercase mb-3">{{ $settings->locations_label }}</p>
                @endif
                <h2 class="text-3xl lg:text-4xl font-extrabold text-nursery-navy mb-4">
                    {{ $settings->locations_title }}
                    @if ($settings->locations_title_highlight)
                        <span class="highlight-wave">{{ $settings->locations_title_highlight }}</span>
                    @endif
                </h2>
                @if ($settings->locations_subtitle)
                    <p class="text-gray-500">{{ $settings->locations_subtitle }}</p>
                @endif
            </div>

            @if ($locations->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($locations as $location)
                        <article class="bg-white rounded-3xl overflow-hidden shadow-sm hover:shadow-md transition">
                            <div class="relative h-44">
                                @if ($location->image)
                                    <img src="{{ $imageUrl($location->image) }}" alt="{{ $location->city }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-nursery-blue to-nursery-green"></div>
                                @endif
                                <div class="absolute inset-0 bg-gradient-to-t from-black/55 via-black/10 to-transparent"></div>
                                <span class="absolute top-3 left-3 text-xs font-bold text-white px-3 py-1 rounded-full" style="background-color: {{ $location->badge_color }}">
                                    {{ $location->city }}
                                </span>
                                <div class="absolute bottom-3 left-4 text-white">
                                    @if ($location->country)
                                        <p class="text-[10px] uppercase tracking-wider opacity-90">{{ $location->country }}</p>
                                    @endif
                                    <p class="font-extrabold text-lg leading-tight">{{ $location->city }}</p>
                                </div>
                            </div>
                            <div class="p-5 space-y-3 text-sm text-gray-600">
                                @if ($location->address)
                                    <p class="flex gap-2"><span>📍</span><span>{{ $location->address }}</span></p>
                                @endif
                                @if ($location->phone)
                                    <p class="flex gap-2"><span>📞</span><a href="tel:{{ $location->phone }}" class="hover:text-nursery-teal">{{ $location->phone }}</a></p>
                                @endif
                                @if ($location->email)
                                    <p class="flex gap-2"><span>✉️</span><a href="mailto:{{ $location->email }}" class="hover:text-nursery-teal break-all">{{ $location->email }}</a></p>
                                @endif
                                @if ($location->working_hours)
                                    <p class="flex gap-2"><span>🕒</span><span>{{ $location->working_hours }}</span></p>
                                @endif
                                <a href="{{ $location->visit_url ?: '#contact' }}" class="inline-flex items-center gap-1 pt-2 font-bold text-nursery-teal hover:underline">
                                    Plan a Visit <span aria-hidden="true">↗</span>
                                </a>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
            <div class="text-right mt-4 text-3xl" aria-hidden="true">✈️</div>
        </div>
    </section>

    {{-- Programs --}}
    <section id="programs" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-12">
                @if ($settings->programs_label)
                    <p class="text-nursery-teal font-bold text-sm tracking-[0.2em] uppercase mb-3">{{ $settings->programs_label }}</p>
                @endif
                <h2 class="text-3xl lg:text-4xl font-extrabold text-nursery-navy mb-4">
                    {{ $settings->programs_title }}
                    @if ($settings->programs_title_highlight)
                        <span class="highlight-wave">{{ $settings->programs_title_highlight }}</span>
                    @endif
                </h2>
                @if ($settings->programs_subtitle)
                    <p class="text-gray-500">{{ $settings->programs_subtitle }}</p>
                @endif
            </div>

            @if ($programs->isNotEmpty())
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach ($programs as $program)
                        <article class="rounded-[1.75rem] overflow-hidden shadow-sm" style="background-color: {{ $program->color }}">
                            <div class="h-40 overflow-hidden" style="border-radius: 0 0 50% 50% / 0 0 28% 28%;">
                                @if ($program->image)
                                    <img src="{{ $imageUrl($program->image) }}" alt="{{ $program->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-white/40 flex items-center justify-center text-4xl">{{ $program->icon ?: '📚' }}</div>
                                @endif
                            </div>
                            <div class="px-5 pb-6 pt-2 text-center -mt-6 relative">
                                <div class="w-12 h-12 mx-auto mb-3 rounded-full flex items-center justify-center text-xl shadow-sm bg-white" style="outline: 4px solid {{ $program->icon_color }}">
                                    {{ $program->icon ?: '⭐' }}
                                </div>
                                <h3 class="font-extrabold text-lg text-nursery-navy">{{ $program->title }}</h3>
                                @if ($program->age_range)
                                    <p class="text-sm font-semibold text-gray-600 mt-1">{{ $program->age_range }}</p>
                                @endif
                                @if ($program->description)
                                    <p class="text-sm text-gray-600 mt-3 leading-relaxed">{{ $program->description }}</p>
                                @endif
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </section>

    {{-- Gallery / Instagram --}}
    <section id="gallery" class="py-20 bg-nursery-cream/40">
        <div class="max-w-7xl mx-auto px-4">
            <div class="text-center max-w-3xl mx-auto mb-12">
                @if ($settings->gallery_label)
                    <p class="text-nursery-teal font-bold text-sm tracking-[0.2em] uppercase mb-3">{{ $settings->gallery_label }}</p>
                @endif
                <h2 class="text-3xl lg:text-4xl font-extrabold text-nursery-navy mb-4">
                    {{ $settings->gallery_title }}
                    @if ($settings->gallery_title_highlight)
                        <span class="highlight-wave">{{ $settings->gallery_title_highlight }}</span>
                    @endif
                </h2>
                @if ($settings->gallery_subtitle)
                    <p class="text-gray-500">{{ $settings->gallery_subtitle }}</p>
                @endif
            </div>

            @if ($galleryItems->isNotEmpty())
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach ($galleryItems as $item)
                        <div class="aspect-square rounded-2xl overflow-hidden bg-white shadow-sm">
                            <img src="{{ $imageUrl($item->image) }}" alt="{{ $item->alt ?? 'Gallery' }}" class="w-full h-full object-cover hover:scale-105 transition duration-300">
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach (range(1, 8) as $i)
                        <div class="aspect-square rounded-2xl bg-gradient-to-br from-nursery-blue/40 via-nursery-pink/30 to-nursery-yellow/40 flex items-center justify-center text-3xl">
                            {{ ['📷','🎨','📖','🍎','🧱','👧','🧒','✏️'][$i - 1] }}
                        </div>
                    @endforeach
                </div>
                <p class="text-center text-sm text-gray-400 mt-4">Upload gallery images from Admin → Gallery</p>
            @endif

            <div class="text-center mt-10">
                <a href="{{ $settings->instagram_url ?: '#gallery' }}" @if($settings->instagram_url) target="_blank" rel="noopener" @endif
                    class="bg-nursery-teal hover:bg-nursery-teal-dark text-white font-bold px-8 py-3 rounded-full transition">
                    {{ $settings->gallery_cta }}
                </a>
            </div>
        </div>
    </section>

    {{-- Contact --}}
    <section id="contact" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 grid lg:grid-cols-2 gap-12 items-start">
            <div>
                @if ($settings->contact_label)
                    <p class="text-nursery-teal font-bold text-sm tracking-[0.2em] uppercase mb-3">{{ $settings->contact_label }}</p>
                @endif
                <h2 class="text-3xl lg:text-4xl font-extrabold text-nursery-navy mb-4">
                    {{ $settings->contact_title }}
                    @if ($settings->contact_title_highlight)
                        <span class="highlight-brush">{{ $settings->contact_title_highlight }}</span>
                    @endif
                    <span class="inline-block ml-1" aria-hidden="true">✈️</span>
                </h2>
                @if ($settings->contact_subtitle)
                    <p class="text-gray-600 mb-8 max-w-md leading-relaxed">{{ $settings->contact_subtitle }}</p>
                @endif

                <div class="space-y-4">
                    @if ($settings->contact_email)
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-nursery-teal text-white flex items-center justify-center text-xl">✉️</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-400">E-mail</p>
                                <a href="mailto:{{ $settings->contact_email }}" class="font-bold text-nursery-navy hover:text-nursery-teal">{{ $settings->contact_email }}</a>
                            </div>
                        </div>
                    @endif
                    @if ($settings->contact_phone)
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-nursery-teal text-white flex items-center justify-center text-xl">📞</div>
                            <div>
                                <p class="text-sm font-semibold text-gray-400">Phone number</p>
                                <a href="tel:{{ $settings->contact_phone }}" class="font-bold text-nursery-navy hover:text-nursery-teal">{{ $settings->contact_phone }}</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <div class="bg-white rounded-3xl shadow-[0_20px_60px_rgba(46,158,148,0.12)] border border-gray-100 p-6 sm:p-8">
                @if (session('contact_success'))
                    <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm">
                        {{ session('contact_success') }}
                    </div>
                @endif

                @if (isset($errors) && $errors->any())
                    <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('contact.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Jane Smith" required
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-nursery-teal/40 focus:border-nursery-teal">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="jane@example.com" required
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-nursery-teal/40 focus:border-nursery-teal">
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label for="program" class="block text-sm font-semibold text-gray-700 mb-1">Program</label>
                            <select id="program" name="program"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-nursery-teal/40 focus:border-nursery-teal bg-white">
                                <option value="">Select...</option>
                                @foreach ($programs as $program)
                                    <option value="{{ $program->title }}" @selected(old('program') === $program->title)>{{ $program->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="child_age" class="block text-sm font-semibold text-gray-700 mb-1">Child's Age</label>
                            <input type="text" id="child_age" name="child_age" value="{{ old('child_age') }}" placeholder="e.g. 3 years"
                                class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-nursery-teal/40 focus:border-nursery-teal">
                        </div>
                    </div>
                    <div>
                        <label for="message" class="block text-sm font-semibold text-gray-700 mb-1">Message</label>
                        <textarea id="message" name="message" rows="5" required placeholder="Type your message..."
                            class="w-full rounded-xl border border-gray-200 px-4 py-3 focus:outline-none focus:ring-2 focus:ring-nursery-teal/40 focus:border-nursery-teal resize-none">{{ old('message') }}</textarea>
                    </div>
                    <button type="submit"
                        class="w-full bg-nursery-teal hover:bg-nursery-teal-dark text-white font-bold py-3.5 rounded-full transition flex items-center justify-center gap-3">
                        <span class="w-7 h-7 rounded-full bg-white/20 flex items-center justify-center text-sm">→</span>
                        Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    {{-- Newsletter --}}
    <section class="bg-nursery-yellow py-8">
        <div class="max-w-7xl mx-auto px-4 flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <span class="text-3xl">🧸</span>
                <p class="font-extrabold text-nursery-navy">{{ $settings->newsletter_title }}</p>
            </div>
            <a href="#contact" class="bg-nursery-teal hover:bg-nursery-teal-dark text-white font-bold px-6 py-2.5 rounded-full transition">Enquire Now</a>
        </div>
    </section>

    {{-- Footer --}}
    <footer class="bg-nursery-navy text-gray-300 py-12">
        <div class="max-w-7xl mx-auto px-4 grid md:grid-cols-3 gap-8">
            <div>
                <div class="flex items-center gap-2 mb-4">
                    <span class="text-2xl">🌈</span>
                    <span class="font-extrabold text-white text-lg">{{ $settings->site_name }}</span>
                </div>
                <p class="text-sm leading-relaxed">{{ $settings->footer_about }}</p>
            </div>
            <div>
                <h4 class="font-bold text-white mb-4 uppercase text-sm tracking-wide">Quick Links</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="#home" class="hover:text-white">Home</a></li>
                    <li><a href="#about" class="hover:text-white">About Us</a></li>
                    <li><a href="#programs" class="hover:text-white">Programs</a></li>
                    <li><a href="#contact" class="hover:text-white">Contact Us</a></li>
                </ul>
            </div>
            <div>
                <h4 class="font-bold text-white mb-4 uppercase text-sm tracking-wide">Contact Us</h4>
                <ul class="space-y-2 text-sm">
                    @if ($settings->contact_address)
                        <li>{{ $settings->contact_address }}</li>
                    @endif
                    @if ($settings->contact_phone)
                        <li><a href="tel:{{ $settings->contact_phone }}" class="hover:text-white">{{ $settings->contact_phone }}</a></li>
                    @endif
                    @if ($settings->contact_email)
                        <li><a href="mailto:{{ $settings->contact_email }}" class="hover:text-white">{{ $settings->contact_email }}</a></li>
                    @endif
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto px-4 mt-8 pt-8 border-t border-white/10 text-center text-sm">
            &copy; {{ date('Y') }} {{ $settings->site_name }}. All rights reserved.
        </div>
    </footer>
@endsection
