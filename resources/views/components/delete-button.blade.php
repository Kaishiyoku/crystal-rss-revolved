@props(['url' => null])

<form class="inline-block" method="post" action="{{ $url }}">
    @csrf

    <input type="hidden" name="_method" value="delete"/>

    <button type="submit" data-confirm="{{ __('Are you sure?') }}" {{ $attributes->merge(['class' => 'inline-flex items-center justify-center px-4 py-2 bg-warning-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-warning-500 focus:outline-none focus:border-warning-700 focus:ring focus:ring-warning-200 active:bg-warning-600 disabled:opacity-25 transition']) }}>
        @if ($slot->isEmpty())
            {{ __('Delete') }}
        @else
            {{ $slot }}
        @endif
    </button>
</form>
