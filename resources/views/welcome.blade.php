<x-app-layout>
    <div class="flex flex-col justify-center items-center min-h-screen space-y-4">
        <x-application-logo/>
        <h1 class="py-4 px-8 bg-primary dark:bg-primary_dark rounded-xl text-2xl font-bold text-white">{{ __("messages.welcome") }}</h1>
        <div class="flex flex-row items-center space-x-4">
            <x-button onclick="window.location='{{ route('login') }}'"
                      class="!bg-primary dark:!bg-primary_dark hover:!bg-secondary dark:hover:!bg-secondary_dark">
                {{ __('messages.login') }}
            </x-button>
            <div class="inline-block h-10 w-0.5 bg-primary dark:bg-primary_dark rounded-xl"></div>
            <x-button onclick="window.location='{{ route('register') }}'"
                      class="!bg-primary dark:!bg-primary_dark hover:!bg-secondary dark:hover:!bg-secondary_dark">
                {{ __('messages.register') }}
            </x-button>
        </div>
    </div>
</x-app-layout>