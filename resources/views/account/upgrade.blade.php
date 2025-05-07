<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('messages.account') }}
        </h2>
    </x-slot>
    <div class="m-auto max-w-2xl w-full">
        <div class="flex flex-col items-center justify-center bg-primary dark:bg-primary_dark rounded-2xl shadow-lg p-8 m-4 overflow-y-auto space-y-4">
            <div class="flex flex-col items-center bg-lighter dark:bg-darker rounded-lg shadow-lg p-8 w-full max-w-2xl m-auto overflow-y-auto space-y-4">
                <h2 class="text-xl font-semibold dark:text-white">{{ __('messages.upgrade_your_account') }}</h2>
                <p class="dark:text-white">{{ __('messages.upgrade_benefits') }}</p>
                <ul class="list-disc list-inside dark:text-white">
                    <li>{{ __('messages.events_advantage') }}</li>
                    <li>{{ __('messages.boosts_advantage') }}</li>
                    <li>{{ __('messages.images_advantage') }}</li>
                    <li>{{ __('messages.filter_advantage') }}</li>
                </ul>
                <form action="{{ route('account.subscription-page') }}" method="GET"
                      class="flex flex-col space-y-4 w-full">
                    <button type="submit"
                            class="mx-auto text-white bg-yellow-400 font-medium hover:bg-yellow-500 rounded-lg text-sm px-5 py-2.5 text-center">
                        <div class="flex flex-row space-x-2 justify-center items-center relative">
                            <img src="{{ asset('vendor/blade-heroicons/o-arrow-up.svg') }}"
                                 alt="{{ __('messages.upgrade') }}"
                                 class="invert w-6 h-6">
                            <p>{{ __('messages.upgrade') }}</p>
                        </div>
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
