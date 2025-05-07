<div class="flex flex-col justify-around space-y-8 rounded-2xl p-6 shadow-md bg-secondary dark:bg-secondary_dark w-full">
    <p class="text-white font-bold text-xl">{{ $title }}</p>
    @if (isset($link))
        <a class="font-medium {{ $textColor ?? 'text-white' }} underline text-center" href="{{ $link }}" target="_blank">{{ $content }}</a>
    @else
        <p class="font-medium {{ $textColor ?? 'text-white' }} text-center">{{ $content }}</p>
    @endif
</div>
