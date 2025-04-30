<x-app-layout>
    <div class="flex flex-col items-center justify-center min-h-screen  max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        <x-application-logo/>
        <h1 class="py-4 px-8 bg-primary dark:bg-primary_dark rounded-lg text-2xl font-bold text-white">{{ __("messages.welcome") }}</h1>
        <div class="flex flex-row items-center space-x-4">
            <x-button onclick="window.location='{{ route('login') }}'">
                {{ __('messages.login') }}
            </x-button>
            <div class="inline-block h-10 w-0.5 bg-primary dark:bg-primary_dark rounded-lg"></div>
            <x-button onclick="window.location='{{ route('register') }}'">
                {{ __('messages.register') }}
            </x-button>
        </div>
    </div>
</x-app-layout>