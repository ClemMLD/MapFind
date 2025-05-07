@props(['iconSize' => 16, 'class' => ''])
<div class="flex flex-row items-center">
    <img src="{{ asset('icons/logo.svg') }}" alt="Logo" class="{{ 'w-' . $iconSize . ' h-' . $iconSize }}">
    <h1 class="font-bold text-white ml-4 text-4xl {{ $class }}">{{ config('app.name', 'Laravel') }}</h1>
</div>