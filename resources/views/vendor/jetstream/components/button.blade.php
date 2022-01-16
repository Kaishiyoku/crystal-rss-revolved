<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center px-4 py-2 bg-indigo-500 dark:bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-500 active:bg-gray-900 dark:active:bg-indigo-800 focus:outline-none focus:border-gray-900 focus:ring focus:ring-indigo-300/75 dark:focus:ring-indigo-400/75 disabled:opacity-25 transition']) }}>
    {{ $slot }}
</button>
