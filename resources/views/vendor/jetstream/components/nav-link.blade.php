@props(['active'])

@php
$classes = ($active ?? false)
            ? 'inline-flex items-center px-1 pt-1 border-b-2 border-primary-400 text-sm font-medium leading-5 text-gray-900 dark:text-gray-100/75 focus:outline-none focus:border-primary-700 dark:focus:border-primary-300 transition'
            : 'inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-500 hover:text-gray-700 dark:hover:text-gray-100/75 hover:border-gray-500/50 focus:outline-none focus:text-gray-700 dark:focus:gray-300 focus:border-gray-500/30 transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
