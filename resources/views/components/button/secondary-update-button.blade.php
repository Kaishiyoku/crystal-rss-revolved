@props(['url' => null])

<form class="inline-block" method="post" action="{{ $url }}">
    @csrf

    <input type="hidden" name="_method" value="put"/>

    <button type="submit" data-confirm="{{ __('Are you sure?') }}" {{ $attributes->except('type')->merge(['class' => classNames('inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow dark:shadow-black/25 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none focus:border-gray-300 dark:focus:border-gray-500 focus:ring-2 ring-offset-2 focus:ring-gray-200 dark:focus:ring-gray-600 dark:focus:ring-offset-gray-800 active:text-gray-800 dark:active:text-gray-100 disabled:opacity-25 transition bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 active:bg-gray-100 dark:active:bg-gray-600')]) }}>
        @if ($slot->isEmpty())
            {{ __('Save') }}
        @else
            {{ $slot }}
        @endif
    </button>
</form>
