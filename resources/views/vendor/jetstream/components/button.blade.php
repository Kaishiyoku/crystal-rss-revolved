<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-primary-500 dark:bg-primary-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-primary-700 dark:hover:bg-primary-500 active:bg-gray-900 dark:active:bg-primary-800 focus:outline-none focus:border-gray-900 focus:ring focus:ring-primary-300/75 dark:focus:ring-primary-400/75 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
