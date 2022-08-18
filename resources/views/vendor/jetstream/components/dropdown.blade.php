@props(['align' => 'right', 'width' => '48', 'containerClasses' => '', 'contentClasses' => 'py-1 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-800/75', 'dropdownClasses' => '', 'mobileFullWidth' => false])

@php
switch ($align) {
    case 'left':
        $alignmentClasses = 'origin-top-left left-0';
        break;
    case 'top':
        $alignmentClasses = 'origin-top';
        break;
    case 'none':
    case 'false':
        $alignmentClasses = '';
        break;
    case 'right':
    default:
        $alignmentClasses = 'origin-top-right right-0';
        break;
}

switch ($width) {
    case '48':
        $width = $mobileFullWidth ? 'sn:w-48' : 'w-48';
        break;
    case '72':
        $width = $mobileFullWidth ? 'sm:w-72' : 'w-72';
        break;
    case '96':
        $width = $mobileFullWidth ? 'sm:w-96' : 'w-96';
        break;
}
@endphp

<div class="{{ classNames('relative', $containerClasses) }}" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open" class="{{ classNames(['inline-block' => !$mobileFullWidth]) }}">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-75"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="{{ classNames('absolute z-50 mt-2 rounded-md shadow-lg dark:dark:shadow-black/50', $alignmentClasses, $dropdownClasses, $width, ['w-full' => $mobileFullWidth]) }}"
            style="display: none;"
            @click="open = false">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 max-h-96 overflow-auto scrollbar-custom {{ $contentClasses }}">
            {{ $content }}
        </div>
    </div>
</div>
