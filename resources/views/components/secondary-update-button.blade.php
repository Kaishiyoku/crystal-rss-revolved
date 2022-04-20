@props(['url' => null])

<form class="inline-block" method="post" action="{{ $url }}">
    @csrf

    <input type="hidden" name="_method" value="put"/>

    <button type="submit" data-confirm="{{ __('Are you sure?') }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-white dark:bg-gray-900/50 border border-gray-500/30 dark:border-gray-700/50 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-500/75 uppercase tracking-widest shadow hover:text-gray-500 dark:hover:text-gray-500/30 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:border-gray-500/30 dark:focus:border-gray-500 focus:ring focus:ring-gray-500/20 dark:focus:ring-gray-700/50 active:text-gray-900/50 dark:active:text-gray-500/20 active:bg-gray-50 dark:active:bg-gray-700/50 disabled:opacity-25 transition']) }}>
        @if ($slot->isEmpty())
            {{ __('Save') }}
        @else
            {{ $slot }}
        @endif
    </button>
</form>
