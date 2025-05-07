@props(['onclick' => '', 'icon' => '', 'alt' => 'Add', 'class' => 'bottom-0', 'id' => ''])

<button
        @if($id) id="{{ $id }}" @endif class="m-5 hover:bg-tertiary dark:hover:bg-tertiary_dark transition bg-secondary dark:bg-secondary_dark backdrop-blur-md rounded-2xl {{ $class }}"
        onclick="{{ $onclick }}">
    <img src="{{ asset('vendor/blade-heroicons/s-' . $icon . '.svg') }}" class="w-10 h-10 m-2 invert"
         alt="{{ $alt ?? '' }}">
</button>