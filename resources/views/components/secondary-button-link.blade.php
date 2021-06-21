<a href="{{ $url }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 text-gray-800 bg-white border border-gray-800 rounded-md font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</a>
