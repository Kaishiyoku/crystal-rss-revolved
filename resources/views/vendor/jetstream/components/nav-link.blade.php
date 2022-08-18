@props(['active'])

@php
$classes = ($active ?? false)
            ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-white px-3 py-2 rounded-md text-sm font-medium transition'
            : 'text-gray-900 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-white px-3 py-2 rounded-md text-sm font-medium transition';
@endphp

<a {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</a>
