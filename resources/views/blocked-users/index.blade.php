<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.blocked_users') }}
        </h2>
    </x-slot>
    @if (isset($users) && count($users) == 0)
        <div class="text-white flex items-center justify-center h-screen">
            <p class="bg-primary dark:bg-primary_dark rounded-2xl p-4">
                {{ __('messages.no_blocked_users') }}
            </p>
        </div>
    @else
        <ul class="list-group m-12 space-y-4">
            @foreach ($users as $user)
                <div class="flex flex-row items-center justify-between list-group-item bg-primary dark:bg-primary_dark hover:bg-secondary hover:dark:bg-secondary_dark transition shadow rounded-2xl p-4">
                    <a href="{{ route('users.show', [$user]) }}" class="flex flex-row items-center space-x-4">
                        @if ($user->image)
                            <img src="{{ config('app.url') . '/storage/' . $user->image }}"
                                 alt="{{ __( 'messages.user_image' ) }}"
                                 class="object-cover w-10 h-10 rounded-full">
                        @else
                            <x-heroicon-s-user-circle class="w-10 h-10 text-white"/>
                        @endif
                        <p class="font-bold text-white">{{ $user->name }} </p>
                    </a>
                    <x-button icon="no-symbol" onclick="unblockUser({{$user->id}})"
                              danger>{{ __('messages.unblock') }}</x-button>
                </div>
            @endforeach
        </ul>
    @endif
</x-app-layout>

<script>
    function unblockUser(id) {
        const xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function () {
            if (this.readyState === 4 && this.status === 204) {
                location.reload();
            }
        };
        xhttp.open('DELETE', "{{ route('blocked-users.destroy', '') }}" + '/' + id, true);
        xhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhttp.send('user_id=' + id + '&_token={{ csrf_token() }}');
    }
</script>
