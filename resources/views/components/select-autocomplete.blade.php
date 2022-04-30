@props(['name' => null, 'value' => null, 'autocompleteValues' => [], 'autocompleteClass' => ''])

<div x-data="selectAutocomplete()" {{ $attributes->merge(['class' => 'relative mt-1']) }}>
    <x-jet-input
        type="text"
        class="w-full placeholder-gray-400 dark:placeholder-gray-500 text-ellipsis overflow-hidden whitespace-nowrap pl-4 pr-12"
        autoComplete="off"
        :name="$name"
        :value="$value"
        x-model="inputValue"
        x-ref="inputElement"
        @focusin="isFocused = true"
        @click.away="isFocused = false"
        @keyup="isValueSelected = false"
        {{ $attributes }}
    />

    <button
        type="button"
        class="flex justify-center items-center absolute top-[1px] right-0 w-10 h-10 text-gray-500 border-l border-r border-gray-200 dark:border-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 rounded-r-md"
        @click.stop="$refs.inputElement ? isFocused ? isFocused = false : $refs.inputElement.focus() : isFocused = !isFocused"
    >
        <x-heroicon-o-chevron-up class="w-5 h-5" x-show="isFocused" x-cloak/>
        <x-heroicon-o-chevron-down class="w-5 h-5" x-show="!isFocused"/>
    </button>

    <div
        x-cloak
        x-show="isFocused"
        @click.stop=""
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute w-full z-50 mr-4 mt-2 rounded-md shadow-lg origin-top-right"
    >
        <div class="{{ classNames('rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white overflow-y-auto min-w-[300px] sm:min-w-[350px] dark:bg-gray-800 dark:border-gray-600 dark:focus:border-primary-500 dark:focus:ring-primary-500 max-h-[200px]', $autocompleteClass) }}">
            <div x-show="filteredAutocompleteValues().length === 0" class="w-full px-4 py-2 text-sm leading-5 text-gray-700">
                {{ __('No entries found.') }}
            </div>

            <template x-for="autocompleteValue in filteredAutocompleteValues()" :key="autocompleteValue.label">
                <button
                    class="w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 dark:text-gray-400 cursor-pointer focus:outline-none transition duration-150 ease-in-out"
                    :class="{ 'text-white dark:text-white bg-primary-500 hover:bg-primary-600 focus:bg-primary-700': inputValue === autocompleteValue.label, 'hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100': inputValue !== autocompleteValue.label }"
                    tabindex="0"
                    @click.stop="selectValue(autocompleteValue)"
                    @keydown.enter.prevent="selectValue(autocompleteValue)"
                >
                    <div x-text="autocompleteValue.label"></div>
                    <div
                        x-text="autocompleteValue.description"
                        class="text-xs "
                        :class="{ 'text-gray-300': inputValue === autocompleteValue.label, 'text-gray-500': inputValue === autocompleteValue.label }"
                    >
                    </div>
                </button>
            </template>
        </div>
    </div>
</div>

<script type="text/javascript">
    function selectAutocomplete() {
        return {
            autocompleteValues: @json($autocompleteValues),
            isFocused: false,
            isValueSelected: false,
            inputValue: @json($value),
            init() {
                this.isValueSelected = !!this.inputValue;
            },
            selectValue(autocompleteValue) {
                this.isValueSelected = true;
                this.isFocused = false;
                this.inputValue = autocompleteValue.label;
            },
            filteredAutocompleteValues() {
                if (this.isValueSelected || !this.inputValue) {
                    return this.autocompleteValues;
                }

                const trimmedInputValue = this.inputValue.trim().toLowerCase();

                return this.autocompleteValues.filter((item) => {
                    const labelContainsStr = item.label.toLowerCase().includes(trimmedInputValue);
                    const descriptionContainsStr = item.description.toLowerCase().includes(trimmedInputValue);

                    return labelContainsStr || descriptionContainsStr;
                });
            },
        };
    }
</script>
