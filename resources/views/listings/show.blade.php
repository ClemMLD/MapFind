<x-app-layout class="h-full">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ $listing->title }}
        </h2>
    </x-slot>
    <div class="flex flex-col relative lg:flex-row flex-grow justify-around bg-primary dark:bg-primary_dark rounded-2xl m-4 p-12 space-x-0 space-y-8 lg:space-y-0 lg:space-x-8 lg:h-full h-full @if($listing->boosted) border-4 border-yellow-500 @endif">
        @if ($listing->boosted)
            <div class="absolute -top-4 right-4 mx-auto text-white bg-yellow-400 font-medium rounded-xl text-sm px-5 py-2.5 text-center">
                <div class="flex flex-row space-x-2 justify-center items-center relative">
                    <x-heroicon-s-star class="w-6 h-6 text-wrap"/>
                    <p class="font-bold">{{ __('messages.boosted') }}</p>
                </div>
            </div>
        @endif
        <x-carousel :listing="$listing"/>
        <div class="border-2 rounded-lg border-tertiary dark:border-tertiary_dark h-full lg:h-auto lg:w-0 w-full dark:border-dark"></div>
        <div class="flex flex-col space-y-4 w-full lg:w-1/2 self-center justify-center items-center">
            <x-card title="{{ __('messages.description') }}"
                    content="{{ $listing->description }}"/>
            <div class="md:grid md:grid-cols-2 space-y-4 md:space-y-0 gap-4 w-full justify-between">
                <x-card title="{{ __('messages.price') }}"
                        content="{{ $listing->price . config('currency.' . $listing->currency) }}"
                        textColor="text-white"/>
                <x-card title="{{ __('messages.address') }}" content="{{ $listing->address }}"
                        link="https://www.google.com/maps/place/{{$listing->address}}/"
                        textColor="text-white"/>
            </div>
            @if ($listing->user->id === auth()->user()->id)
                @if (!$listing->boosted && auth()->user()->boosts > 0)
                    <x-button onclick="boostListing()"
                              icon="star" class="!bg-yellow-400 hover:!bg-yellow-500">
                        {{ __('messages.boost') }}
                    </x-button>
                @endif
                <x-button onclick="window.location.href = '{{ route('listings.edit', [$listing]) }}'"
                          icon="pencil">
                    {{ __('messages.edit') }}
                </x-button>
                <x-button onclick="deleteListing()"
                          danger
                          icon="trash">
                    {{ __('messages.delete') }}
                </x-button>
            @else
                <x-button onclick="window.location.href = '{{ route('messages.show', [$listing->user]) }}'"
                          icon="chat-bubble-oval-left">
                    {{ __('messages.contact') }}
                </x-button>
            @endif
        </div>
    </div>
</x-app-layout>

<script>
    function boostListing() {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 202) {
                window.location.reload();
            }
        };
        xhttp.open('POST', "{{ route('listings.boost', ['id' => $listing->id]) }}");
        xhttp.setRequestHeader('Accept', 'application/json');
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhttp.send();
    }

    function deleteListing() {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 204) {
                window.location.href = '{{ route('listings.index') }}';
            }
        };
        console.log("{{ route('listings.destroy', [$listing]) }}");
        xhttp.open('DELETE', "{{ route('listings.destroy', [$listing]) }}");
        xhttp.setRequestHeader('Accept', 'application/json');
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        // xhttp.send();
    }
</script>
