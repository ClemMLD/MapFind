<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.my_favorites') }}
        </h2>
    </x-slot>
    @if (isset($favorites) && count($favorites) == 0)
        <div class="text-white flex items-center justify-center h-screen">
            <p class="bg-primary dark:bg-primary_dark rounded-lg p-4">
                {{ __('messages.no_favorites') }}
            </p>
        </div>
    @else
        <ul class="list-group m-12 space-y-4">
            @foreach ($favorites as $favorite)
                <div class="flex flex-col md:flex-row justify-between items-center list-group-item bg-primary dark:bg-primary_dark hover:bg-secondary dark:hover:bg-secondary_dark shadow transition rounded-2xl p-4 md:space-x-4">
                    <a href="{{ route('listings.show', [$favorite->listing]) }}" class="flex flex-row items-center space-x-4">
                        @if ($favorite->listing->images->isEmpty())
                            <x-heroicon-s-photo id="listing-image" class="w-10 h-10 text-white"/>
                        @else
                            <img src="{{ config('app.url') . '/storage/' . $favorite->listing->images[0]->name }}"
                                 alt="{{ __( 'messages.listing_image' ) }}"
                                 class="object-cover w-10 h-10 rounded-full">
                        @endif
                        <p class="font-bold text-white">{{ Str::limit($favorite->listing->title, 20) }} </p>
                    </a>
                    <x-floating-button
                            danger
                            icon="trash"
                            class="m-0 !bg-red-500 hover:!bg-red-600"
                            onclick="removeFavorite({{$favorite->listing->id}})"/>
                </div>
            @endforeach
        </ul>
    @endif
</x-app-layout>

<script>
    function removeFavorite(id) {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 204) {
                location.reload();
            }
        };
        xhttp.open('DELETE', "{{ route('favorites.destroy', '') }}" + '/' + id, true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-Token', '{{ csrf_token() }}');
        xhttp.send();
    }
</script>
