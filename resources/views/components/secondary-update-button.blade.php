@props(['url' => null])

<form class="inline-block" method="post" action="{{ $url }}">
    @csrf

    <input type="hidden" name="_method" value="put"/>

    <button type="submit" data-confirm="{{ __('Are you sure?') }}" {{ $attributes->merge(['class' => 'inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 disabled:opacity-25 transition']) }}>
        @if ($slot->isEmpty())
            {{ __('Save') }}
        @else
            {{ $slot }}
        @endif
    </button>
</form>
