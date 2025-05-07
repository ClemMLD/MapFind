<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.account') }}
        </h2>
    </x-slot>
    <div class="m-auto max-w-2xl w-full">
        <div class="flex flex-col items-center justify-center bg-primary dark:bg-primary_dark rounded-2xl shadow-lg p-8 m-4 overflow-y-auto space-y-4">
            @if (auth()->user()->image)
                <img src="{{ config('app.url') . '/storage/' . auth()->user()->image }}"
                     class="object-cover w-64 h-64 rounded-full"
                     alt="{{ __('messages.avatar') }}">
            @else
                <x-heroicon-o-user-circle id="user-avatar" class="w-64 h-64 text-white"/>
            @endif
            <div class="flex flex-col items-center text-center">
                <h2 class="text-xl font-semibold text-white">{{ auth()->user()->nickname }}</h2>
                <p class="text-white opacity-60">{{ auth()->user()->name }}</p>
            </div>
            <div class="flex flex-col">
                @if (auth()->user()->type === 'standard')
                    <x-button onclick="window.location.href = '{{ route('account.upgrade') }}'"
                              class="!bg-yellow-400 hover:!bg-yellow-500"
                              icon="arrow-up">
                        {{ __('messages.upgrade') }}
                    </x-button>
                @elseif(auth()->user()->type === 'premium')
                    <x-button class="!bg-yellow-500"
                              disabled
                              icon="star">
                        <p class="font-bold">{{ __('messages.premium_user') }}</p>
                    </x-button>
                @endif
            </div>
            <div class="h-1 w-full bg-tertiary dark:bg-tertiary_dark rounded"></div>
            <div class="flex flex-col items-center space-y-4">
                <x-button class="w-full"
                          icon="pencil"
                          onclick="window.location.href = '{{ route('account.edit', [auth()->user()]) }}'">
                    {{ __('messages.edit_account') }}
                </x-button>
                <x-button class="w-full"
                          icon="hand-raised"
                          onclick="window.location.href = '{{ route('blocked-users.index') }}'">
                    {{ __('messages.blocked_users') }}
                </x-button>
            </div>
        </div>
    </div>
</x-app-layout>