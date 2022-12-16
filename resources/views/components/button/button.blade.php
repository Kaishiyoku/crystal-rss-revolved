@php
    $classes = [
        'inline-flex items-center transition ease-in disabled:opacity-50 disabled:cursor-not-allowed',
        'font-semibold text-xs uppercase tracking-widest border focus:outline-none focus:ring-1' => !$link,
        'shadow focus:shadow-md dark:shadow-black/25' => !$plain && !$link,
        'text-white border-primary-600 bg-primary-500 hover:border-primary-700 hover:bg-primary-600 focus:ring-primary-600 dark:border-primary-500 dark:bg-primary-600 dark:hover:border-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-500' => $primary,
        'text-gray-900 border-gray-300 bg-white hover:border-gray-300 hover:bg-gray-100 focus:ring-gray-300 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:border-gray-500 dark:hover:bg-gray-600 dark:focus:ring-gray-600' => $secondary,
        'text-gray-900 border-transparent hover:bg-gray-200 focus:ring-gray-300 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:ring-gray-600' => $plain,
        'text-white border-warning-600 bg-warning-500 hover:border-warning-700 hover:bg-warning-600 focus:ring-warning-600 dark:border-warning-500 dark:bg-warning-600 dark:hover:border-warning-600 dark:hover:bg-warning-700 dark:focus:ring-warning-500' => $danger,
        'text-primary-600 underline decoration-transparent hover:decoration-primary-600 dark:text-primary-400 dark:hover:decoration-primary-400' => $link,
        'rounded-full w-7 h-7 justify-center' => $iconOnly,
        'rounded-md px-4 py-3 sm:py-2' => !$iconOnly,
    ];
@endphp

@if ($action)
    <form class="inline-block" method="{{ $method !== 'post' ? 'post' : $method }}" action="{{ $action }}">
        @csrf

        @if ($method !== 'post')
            <input type="hidden" name="_method" value="{{ $method }}"/>
        @endif
@endif

    @if ($href)
        <a
            x-data="{}"
            href="{{ $href }}"
            @click="{{ $clickHandler() }}"
            @class(array_merge($classes, [$attributes->get('class')]))
            {{ $attributes->except(['class', 'href']) }}
        >
            {{ $slot }}
        </a>
    @else
        <button
            x-data="{}"
            type="{{ $type }}"
            @click="{{ $clickHandler() }}"
            @class([...$classes, $attributes->get('class')])
            {{ $attributes->except(['class', 'type']) }}
        >
            {{ $slot }}
        </button>
    @endif

@if ($action)
    </form>
@endif
