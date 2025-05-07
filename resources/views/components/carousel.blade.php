<div id="carousel" class="w-full lg:w-1/2 self-center flex relative">
    @if ($listing->images->isEmpty())
        <div class="bg-light dark:bg-dark rounded-2xl w-full">
            <x-heroicon-s-photo class="text-white aspect-video object-fill"/>
        </div>
    @else
        <img id="carousel-image" src="{{ config('app.url') . '/storage/' . $listing->images[0]->name }}"
             class="object-cover rounded-2xl mx-auto aspect-video w-full" alt="{{ __('messages.listing_image') }}">
    @endif

    @if (count($listing->images) > 1)
        <button id="prev"
                class="absolute left-2 top-1/2 transform -translate-y-1/2 bg-secondary dark:bg-secondary_dark text-white p-2 rounded-full">
            <x-heroicon-s-chevron-left class="w-4 h-4 text-white"/>
        </button>
        <button id="next"
                class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-secondary dark:bg-secondary_dark text-white p-2 rounded-full">
            <x-heroicon-s-chevron-right class="w-4 h-4 text-white"/>
        </button>
    @endif

    <a href="{{ route('users.show', ['user' => $listing->user]) }}"
       class="flex space-x-2 items-center absolute -bottom-5 right-2 text-xl font-medium px-4 py-2 rounded-xl bg-secondary dark:bg-secondary_dark hover:bg-tertiary dark:hover:bg-tertiary_dark transition text-white">
        @if ($listing->user->image)
            <img src="{{ config('app.url') . '/storage/' . $listing->user->image }}"
                 class="object-cover w-8 h-8 rounded-full"
                 alt="{{ __('messages.avatar') }}">
        @else
            <x-heroicon-s-user-circle class="w-8 h-8 text-white"/>
        @endif
        <p class="hidden md:block">{{ $listing->user->nickname }}</p>
    </a>
    <span class="absolute -bottom-5 left-2 text-xl font-medium px-4 py-1.5 rounded-xl border-4 bg-secondary dark:bg-secondary_dark text-white"
          style="border-color: {{ $listing->category->color }}">
        {{ $listing->category->name[App::getLocale()] ?? '' }}
    </span>
</div>

<script>
    const images = @json($listing->images);
    let currentIndex = 0;

    if (images.length > 1) {
        document.getElementById('prev').addEventListener('click', () => {
            currentIndex = (currentIndex > 0) ? currentIndex - 1 : images.length - 1;
            updateImage();
        });

        document.getElementById('next').addEventListener('click', () => {
            currentIndex = (currentIndex < images.length - 1) ? currentIndex + 1 : 0;
            updateImage();
        });
    }

    function updateImage() {
        document.getElementById('carousel-image').src = `{{ config('app.url') . '/storage/' }}${images[currentIndex].name}`;
    }
</script>