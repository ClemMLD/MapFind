@props(['onclick' => '', 'icon' => 'plus.svg', 'alt' => 'Add', 'class' => 'bottom-0', 'id' => ''])

<button
        @if($id) id="{{ $id }}" @endif class="fixed  right-0 m-5 dark:bg-neutral-800/80 bg-zinc-50/80 backdrop-blur-md rounded-xl {{ $class }}"
        onclick="{{ $onclick }}">
    <img src="{{ asset('vendor/blade-heroicons/' . $icon) }}" class="w-10 h-10 m-2 dark:invert invert-0"
         alt="{{ $alt ?? '' }}">
</button>