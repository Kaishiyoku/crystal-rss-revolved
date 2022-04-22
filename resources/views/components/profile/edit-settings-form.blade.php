<div class="md:grid md:grid-cols-3 md:gap-6" {{ $attributes }}>
    <x-jet-section-title>
        <x-slot name="title">{{ __('Preferences') }}</x-slot>
        <x-slot name="description">{{ __('Edit your preferences.') }}</x-slot>
    </x-jet-section-title>

    {{ html()->modelForm(Auth::user(), 'put', route('user-edit-settings'))->class('mt-5 md:mt-0 md:col-span-2')->open() }}
        <div class="px-4 py-5 sm:p-6 bg-white dark:bg-gray-800 dark:bg-opacity-50 shadow sm:rounded-t-lg">
            <div class="flex space-x-4 mt-5">
                <div class="block mb-4">
                    <label for="is_feed_item_description_visible" class="flex items-center">
                        <x-jet-checkbox id="is_feed_item_description_visible" name="is_feed_item_description_visible" :checked="old('is_feed_item_description_visible', Auth::user()->is_feed_item_description_visible)"/>
                        <span class="ml-2 text-sm text-gray-600 dark:text-gray-500">{{ __('validation.attributes.is_feed_item_description_visible') }}</span>
                    </label>

                    @error('is_feed_item_description_visible')
                        <x-validation-error>{{ $message }}</x-validation-error>
                    @enderror
                </div>
            </div>
        </div>

        <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-800 dark:bg-opacity-25 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <x-jet-button>{{ __('Save') }}</x-jet-button>
        </div>
    {{ html()->closeModelForm() }}
</div>

