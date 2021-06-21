@props(['theme' => 'default', 'url' => null])

<form class="inline-block" method="post" action="{{ $url }}">
    @csrf

    <input type="hidden" name="_method" value="delete"/>

    <button type="submit" data-confirm="{{ __('Are you sure?') }}" {{ $attributes->merge(['class' => classNames('inline-flex items-center px-4 py-2 border border-transparent rounded-md font-semibold text-xs uppercase tracking-widest focus:outline-none disabled:opacity-25 transition ease-in-out duration-150', ['bg-red-600 text-white bg-red-600 text-white hover:bg-red-700 active:bg-red-900 focus:border-red-900 focus:ring ring-red-300' => $theme === 'default', 'text-red-600 hover:bg-red-200 hover:border-red-200 focus:text-red-700 focus:bg-red-300 focus:border-red-300' => $theme === 'plain'])]) }}>
        @if ($slot->isEmpty())
            {{ __('Delete') }}
        @else
            {{ $slot }}
        @endif
    </button>
</form>
