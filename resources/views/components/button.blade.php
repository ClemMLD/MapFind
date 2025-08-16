@props(['type' => 'submit', 'class' => '', 'onclick' => '', 'danger' => false, 'disabled' => false, 'icon' => ''])

<button type="{{ $type }}" {{ $attributes->merge(['class' => 'flex flex-row justify-center transition ' . (!$disabled ? 'hover:bg-tertiary dark:hover:bg-tertiary_dark' : '') . ' bg-secondary dark:bg-secondary_dark py-2 px-4 rounded-xl text-white text-sm ' . ($danger ? '!bg-red-500 hover:!bg-red-600' : '') . ' ' . $class]) }} {{ $disabled ? 'disabled' : '' }} @if($onclick) onclick="{{ $onclick }}" @endif>
    @if($icon)
        <img src="{{ asset('vendor/blade-heroicons/s-' . $icon . '.svg') }}" alt="Icon" class="w-5 h-5 invert mr-2">
    @endif
    {{ $slot }}
</button>