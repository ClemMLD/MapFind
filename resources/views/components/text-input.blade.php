@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'border-transparent focus:border-tertiary focus:dark:border-tertiary_dark bg-secondary dark:bg-secondary_dark text-white focus:border-tertiary dark:focus:border-tertiary_dark focus:ring-primary dark:focus:ring-primary_dark rounded-md shadow-sm']) }}>
