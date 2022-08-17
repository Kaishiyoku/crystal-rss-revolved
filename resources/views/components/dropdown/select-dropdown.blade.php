@props(['align' => 'left', 'placeholder' => null, 'autocompleteValues' => [], 'value' => null, 'subValue', 'width' => '48', 'mobileFullWidth' => false, 'triggerClass' => ''])

@php
switch ($width) {
    case '48':
        $width = $mobileFullWidth ? 'sm:w-48' : 'w-48';
        break;
    case '72':
        $width = $mobileFullWidth ? 'sm:w-72' : 'w-72';
        break;
    case '96':
        $width = $mobileFullWidth ? 'sm:w-96' : 'w-96';
        break;
}
@endphp

<x-jet-dropdown :align="$align" :width="$width" :mobile-full-width="$mobileFullWidth">
    <x-slot name="trigger">
        <button type="button" class="{{ classNames('inline-flex items-center justify-between px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-400 focus:outline-none shadow transition', $width, $triggerClass, ['w-full' => $mobileFullWidth]) }}">
            <div class="flex text-ellipsis overflow-hidden whitespace-nowrap">
                <div>{{ $value ?? $placeholder }}</div>

                @if ($subValue)
                    <div class="mx-1 select-none text-gray-300 dark:text-gray-600">|</div>
                    <div class="text-gray-300 dark:text-gray-600">{{ $subValue }}</div>
                @endif
            </div>

            <svg class="shrink-0 h-4 w-4 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
            </svg>
        </button>
    </x-slot>

    <x-slot name="content">
        @foreach ($autocompleteValues as $autocompleteValue)
            @if ($autocompleteValue)
                <x-jet-dropdown-link :href="\Illuminate\Support\Arr::get($autocompleteValue, 'url')" :active="\Illuminate\Support\Arr::get($autocompleteValue, 'label') === $value">
                    <div>
                        {{ \Illuminate\Support\Arr::get($autocompleteValue, 'label') }}
                    </div>
                    <div class="{{ classNames('text-xs', ['text-gray-200 dark:text-gray-300' => \Illuminate\Support\Arr::get($autocompleteValue, 'label') === $value, 'text-gray-500' => \Illuminate\Support\Arr::get($autocompleteValue, 'label') !== $value]) }}">
                        {{ \Illuminate\Support\Arr::get($autocompleteValue, 'description') }}
                    </div>
                </x-jet-dropdown-link>
            @else
                <x-dropdown.dropdown-divider/>
            @endif
        @endforeach
    </x-slot>
</x-jet-dropdown>
