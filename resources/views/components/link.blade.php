<a {{ $attributes->merge(['class' => 'text-primary-600 dark:text-primary-500 border-b-2 border-transparent hover:text-primary-800 dark:hover:text-primary-300 hover:border-primary-300 dark:hover:border-primary-700 focus:text-primary-900 dark:focus:text-primary-200 focus:border-primary-400 dark:focus:border-primary-600 transition']) }}>
    {{ $slot }}
</a>
