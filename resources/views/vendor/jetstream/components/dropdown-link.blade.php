@props(['active' => false])

<a {{ $attributes->merge(['class' => classNames('block px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 cursor-pointer focus:outline-none transition duration-150 ease-in-out', ['text-white dark:text-white bg-primary-500 hover:bg-primary-600 focus:bg-primary-700' => $active, 'hover:bg-gray-100 dark:hover:bg-gray-800 focus:bg-gray-100' => !$active])]) }}>
    {{ $slot }}
</a>
