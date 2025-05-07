<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.my_listings') }}
        </h2>
    </x-slot>
    @if ($listings->isEmpty())
        <div class="text-white flex items-center justify-center h-screen">
            <p class="bg-primary dark:bg-primary_dark rounded-lg p-4">
                No listings found.
            </p>
        </div>
    @else
        <ul class="list-group m-12 space-y-4">
            @foreach ($listings as $listing)
                <a href="{{ route('listings.show', [$listing]) }}" class="block transition">
                    <div class="flex flex-row items-center list-group-item bg-primary dark:bg-primary_dark hover:bg-secondary dark:hover:bg-secondary_dark shadow transition rounded-lg p-4 space-x-4">
                        @if ($listing->images->isEmpty())
                            <x-heroicon-s-photo id="listing-image" class="w-10 h-10 text-white"/>
                        @else
                            <img src="{{ config('app.url') . '/storage/' . $listing->images[0]->name }}"
                                 alt="{{ __( 'messages.listing_image' ) }}"
                                 class="object-cover w-10 h-10 rounded-full">
                        @endif
                        <p class="font-bold text-white">{{ $listing->title }} </p>
                    </div>
                </a>
            @endforeach
        </ul>
    @endif
</x-app-layout>
