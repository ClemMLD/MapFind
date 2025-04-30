@props(['onclick' => '', 'danger' => false])

<button class="opacity-100 hover:opacity-80 transition bg-secondary dark:bg-secondary_dark py-2 px-4 rounded-lg text-white text-sm {{ $danger ? '!bg-red-500' : '' }}" onclick="{{ $onclick }}">
    {{ $slot }}
</button>