<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-400 uppercase tracking-widest shadow-sm hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:border-gray-400 dark:focus:border-gray-400 focus:ring focus:ring-gray-300 dark:focus:ring-gray-600/75 active:text-gray-800 dark:active:text-gray-200 active:bg-gray-50 dark:active:bg-gray-700 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
