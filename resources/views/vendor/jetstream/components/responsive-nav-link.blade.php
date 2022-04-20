@props(['active'])

@php
$classes = ($active ?? false)
            ? 'block pl-3 pr-4 py-2 border-l-4 border-primary-400 text-base font-medium text-primary-700 dark:text-primary-200 bg-primary-50 dark:bg-primary-500 focus:outline-none focus:text-primary-800 focus:bg-primary-100 focus:border-primary-700 transition'
            : 'block pl-3 pr-4 py-2 border-l-4 border-transparent text-base font-medium text-gray-700/50 dark:text-gray-500/75 hover:text-gray-900/50 dark:hover:text-gray-500/30 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-500/30 focus:outline-none focus:text-gray-900/50 focus:bg-gray-50 focus:border-gray-500/30 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
