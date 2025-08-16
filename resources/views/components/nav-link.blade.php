@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-4 border-tertiary dark:border-tertiary_dark text-sm font-medium hover:text-gray-300 leading-5 text-white focus:outline-none focus:border-tertiary dark:focus:border-tertiary_dark transition duration-150 ease-in-out'
            : 'inline-flex items-center px-1 pt-1 border-b-4 border-transparent text-sm font-medium leading-5 text-white hover:text-gray-300 hover:border-tertiary hover:dark:border-tertiary_dark dark:hover:border-gray-700 focus:outline-none focus:border-gray-300 dark:focus:border-gray-700 transition duration-150 ease-in-out';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
