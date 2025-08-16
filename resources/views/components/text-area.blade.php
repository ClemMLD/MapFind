@props(['disabled' => false])

<textarea @disabled($disabled) {{ $attributes->merge(['class' => 'border-transparent focus:border-tertiary focus:dark:border-tertiary_dark bg-secondary dark:bg-secondary_dark text-white placeholder-white placeholder-opacity-60 focus:ring-2 focus:ring-tertiary dark:focus:ring-tertiary_dark focus:border-none rounded-xl transition shadow-sm', 'placeholder' => __('messages.type_your_message') ]) }}>{{ $slot }}</textarea>
