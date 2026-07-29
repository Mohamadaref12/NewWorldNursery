@php
    use App\Support\ImageUrl;
@endphp

@if ($items->isEmpty())
    <div class="rounded-xl border border-dashed border-gray-300 px-6 py-10 text-center text-sm text-gray-500 dark:border-gray-700 dark:text-gray-400">
        No Instagram images synced yet.
    </div>
@else
    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5">
        @foreach ($items as $item)
            @php
                $url = ImageUrl::make($item->image);
            @endphp

            @if ($url)
                <a
                    href="{{ $item->permalink ?: $url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="group relative block overflow-hidden rounded-xl bg-gray-100 ring-1 ring-gray-950/5 dark:bg-gray-800 dark:ring-white/10"
                    title="{{ $item->alt }}"
                >
                    <img
                        src="{{ $url }}"
                        alt="{{ $item->alt ?? 'Instagram post' }}"
                        class="aspect-square h-full w-full object-cover transition duration-200 group-hover:scale-105"
                        loading="lazy"
                    />
                    @if ($item->alt)
                        <div class="pointer-events-none absolute inset-x-0 bottom-0 bg-gradient-to-t from-black/70 to-transparent p-2 opacity-0 transition group-hover:opacity-100">
                            <p class="line-clamp-2 text-xs text-white">{{ $item->alt }}</p>
                        </div>
                    @endif
                </a>
            @endif
        @endforeach
    </div>
@endif
