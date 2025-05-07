<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.user') }}
        </h2>
    </x-slot>
    <div class="m-auto max-w-2xl w-full">
        <div class="flex flex-col items-center justify-center bg-primary dark:bg-primary_dark rounded-2xl shadow-lg p-8 m-4 overflow-y-auto space-y-4">
            @if ($user->image)
                <img src="{{ config('app.url') . '/storage/' . $user->image }}" alt="{{ __('messages.avatar') }}"
                     class="object-cover w-64 h-64 rounded-full">
            @else
                <x-heroicon-s-user-circle class="w-64 h-64 text-white"/>
            @endif

            <div class="flex flex-col items-center text-center">
                <h2 class="text-xl font-semibold text-white">{{ $user->nickname }}</h2>
                <p class="text-white opacity-60">{{ $user->name }}</p>
            </div>

            <div class="h-1 w-full bg-tertiary dark:bg-tertiary_dark rounded"></div>

            <div class="flex flex-col items-center space-y-4">
                @if (isset($incomingBlocked))
                    <p class="text-white text-center">{{ __('messages.incoming_block') }}</p>
                @elseif (isset($outgoingBlocked))
                    <p class="text-white font-semibold text-center">{{ __('messages.outgoing_block') }}</p>
                    <x-button class="w-full"
                              icon="no-symbol"
                              onclick="unblockUser()">
                        {{ __('messages.unblock') }}
                    </x-button>
                @else
                    <x-button onclick="window.location.href = '{{ route('messages.show', [$user]) }}'"
                              class="w-full"
                              icon="chat-bubble-oval-left">
                        {{ __('messages.send_message') }}
                    </x-button>
                    <x-button onclick="blockUser()"
                              class="w-full"
                              icon="no-symbol">
                        {{ __('messages.block') }}
                    </x-button>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

<script>
    function blockUser() {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 201) {
                location.reload();
            }
        };
        xhttp.open('POST', "{{ route('blocked-users.store', ['user_id' => $user->id]) }}");
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhttp.send();
    }

    function unblockUser() {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 204) {
                location.reload();
            }
        };
        xhttp.open('DELETE', "{{ route('blocked-users.destroy', ['blocked_user' => $user->id]) }}");
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').content);
        xhttp.send();
    }
</script>
