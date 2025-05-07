<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-7xl my-4 mx-auto px-4">
        <div class="p-4 sm:p-8 bg-primary dark:bg-primary_dark shadow rounded-2xl">
            <div class="max-w-lg">
                @include('account.partials.update-profile-information-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-primary dark:bg-primary_dark shadow rounded-2xl">
            <div class="max-w-lg">
                @include('account.partials.update-password-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-primary dark:bg-primary_dark shadow rounded-2xl">
            <div class="max-w-lg">
                @include('account.partials.update-avatar-form')
            </div>
        </div>

        <div class="p-4 sm:p-8 bg-primary dark:bg-primary_dark shadow rounded-2xl">
            <div class="max-w-lg">
                @include('account.partials.delete-user-form')
            </div>
        </div>
    </div>
</x-app-layout>
