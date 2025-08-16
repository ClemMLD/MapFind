<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.upgrade') }}
        </h2>
    </x-slot>
    <div class="m-auto max-w-2xl w-full">
        <div class="flex flex-col items-center justify-center bg-primary dark:bg-primary_dark rounded-2xl shadow-lg p-8 m-4 overflow-y-auto space-y-4">
            <h2 class="text-xl font-semibold text-white">{{ __('messages.upgrade_your_account') }}</h2>
            <p class="text-white">{{ __('messages.upgrade_benefits') }}</p>
            <ul class="list-disc list-inside text-white">
                <li>{{ __('messages.listings_advantage') }}</li>
                <li>{{ __('messages.boosts_advantage') }}</li>
                <li>{{ __('messages.images_advantage') }}</li>
                <li>{{ __('messages.filter_advantage') }}</li>
            </ul>
            <x-button class="!bg-yellow-500 hover:!bg-yellow-600"
                      icon="arrow-up"
                      onclick="window.location.href = '{{ route('account.subscription-page') }}'">
                {{ __('messages.upgrade') }}
            </x-button>
        </div>
    </div>
</x-app-layout>
