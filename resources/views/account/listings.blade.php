@php use Illuminate\Support\Str; @endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.my_listings') }}
        </h2>
    </x-slot>
    @if ($listings->isEmpty())
        <div class="text-white flex items-center justify-center h-screen">
            <p class="bg-primary dark:bg-primary_dark rounded-lg p-4">
                {{ __('messages.no_listings') }}
            </p>
        </div>
    @else
        <ul class="list-group m-12 space-y-4">
            @foreach ($listings as $listing)
                <div class="flex flex-row justify-between items-center list-group-item bg-primary dark:bg-primary_dark hover:bg-secondary dark:hover:bg-secondary_dark shadow transition rounded-2xl p-4 space-x-4">
                    <a href="{{ route('listings.show', [$listing]) }}" class="flex flex-row items-center space-x-4">
                        @if ($listing->images->isEmpty())
                            <x-heroicon-s-photo id="listing-image" class="w-10 h-10 text-white"/>
                        @else
                            <img src="{{ config('app.url') . '/storage/' . $listing->images[0]->name }}"
                                 alt="{{ __( 'messages.listing_image' ) }}"
                                 class="object-cover w-10 h-10 rounded-full">
                        @endif
                        <p class="font-bold text-white">{{ Str::limit($listing->title, 20) }} </p>
                    </a>
                    <div class="flex flex-row items-center space-x-4">
                        @if (!$listing->boosted)
                            <x-floating-button
                                    icon="star"
                                    class="m-0 !bg-yellow-500 hover:!bg-yellow-600"
                                    onclick="boostListing({{ $listing->id }})"/>
                        @endif
                        <x-floating-button
                                icon="pencil"
                                class="m-0"
                                onclick="window.location.href='{{ route('listings.edit', [$listing]) }}'"/>
                        <x-floating-button
                                danger
                                icon="trash"
                                class="m-0 !bg-red-500 hover:!bg-red-600"
                                onclick="deleteListing({{ $listing->id }})"/>
                    </div>
                </div>
            @endforeach
        </ul>
    @endif
</x-app-layout>

<script>
    function boostListing(id) {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 202) {
                window.location.reload();
            }
        };
        xhttp.open('POST', "{{ route('listings.boost', '') }}" + '?id=' + id);
        xhttp.setRequestHeader('Accept', 'application/json');
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhttp.send();
    }

    function deleteListing(id) {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 204) {
                window.location.reload()
            }
        };
        xhttp.open('DELETE', "{{ route('listings.destroy', '') }}" + '/' + id);
        xhttp.setRequestHeader('Accept', 'application/json');
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhttp.send();
    }
</script>
