<div class="flex flex-row items-center">
    <img src="{{ asset('icons/logo.svg') }}" alt="Logo" class="h-{{ $iconSize ?? 16 }} w-{{ $iconSize ?? 16 }}">
    <h1 class="font-bold text-white ml-4 {{ $fontSize ?? 'text-4xl' }}">{{ config('app.name', 'Laravel') }}</h1>
</div>