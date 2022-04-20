<div class="md:grid md:grid-cols-3 md:gap-6" {{ $attributes }}>
    <x-jet-section-title>
        <x-slot name="title">{{ __('Edit theme') }}</x-slot>
        <x-slot name="description">{{ __('Edit the color theme of the site') }}</x-slot>
    </x-jet-section-title>

    <form method="POST" action="{{ route('user-theme.update') }}" class="mt-5 md:mt-0 md:col-span-2">
        @csrf

        <input type="hidden" name="_method" value="put"/>

        <div class="px-4 py-5 sm:p-6 bg-white dark:bg-gray-800 dark:bg-opacity-50 shadow sm:rounded-top-lg">
            <div class="max-w-xl text-sm text-gray-600 dark:text-gray-500">
                @foreach (availableThemeColorFields() as $colorField)
                    <div>
                        <x-jet-label :for="$colorField" value="{{ __('validation.attributes.' . $colorField) }}" />
                        <x-jet-input :id="$colorField" class="block mt-1 w-full" type="color" :name="$colorField" required/>

                        @error($colorField)
                            <x-validation-error>{{ $message }}</x-validation-error>
                        @enderror
                    </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-end space-x-2 px-4 py-3 bg-gray-50 dark:bg-gray-800 dark:bg-opacity-25 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
            <x-jet-button class="ml-4">
                {{ __('Save') }}
            </x-jet-button>

            <x-secondary-update-button :url="route('user-theme.reset')">
                {{ __('Reset theme') }}
            </x-secondary-update-button>
        </div>
    </form>
</div>
