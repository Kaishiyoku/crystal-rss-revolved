<div class="md:grid md:grid-cols-3 md:gap-6" {{ $attributes }}>
    <x-jet-section-title>
        <x-slot name="title">{{ __('Edit color theme') }}</x-slot>
        <x-slot name="description">{{ __('You can change the color theme of the site. the lower the number the lighter the color should be (50 = lightest, 900 = darkest).') }}</x-slot>
    </x-jet-section-title>

    <div class="mt-5 md:mt-0 md:col-span-2">
        <div class="mb-5">
            <x-secondary-update-button :url="route('user-theme.reset')">
                {{ __('Reset color theme') }}
            </x-secondary-update-button>
        </div>

        <form method="POST" action="{{ route('user-theme.update') }}">
            @csrf

            <input type="hidden" name="_method" value="put"/>

            <div class="px-4 py-5 sm:p-6 bg-white dark:bg-gray-900/50 dark:bg-opacity-50 shadow sm:rounded-t-lg">
                <div class="max-w-xl text-sm text-gray-700/50 dark:text-gray-500">
                    @foreach (availableThemeColorFields() as $colorGroup => $colorFields)
                        <div class="text-lg text-gray-900 dark:text-gray-100 pb-2">
                            {{ __(\Illuminate\Support\Str::ucfirst($colorGroup)) }}
                        </div>

                        <div class="grid grid-cols-5">
                            @foreach ($colorFields as $colorField)
                                <div class="mb-4">
                                    <x-jet-label :for="$colorField" :value="Arr::last(explode('_', $colorField))" class="pl-1"/>
                                    <x-jet-input :id="$colorField" class="block mt-1 w-full" type="color" :name="$colorField" required/>

                                    @error($colorField)
                                        <x-validation-error>{{ $message }}</x-validation-error>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center justify-end px-4 py-3 bg-gray-50 dark:bg-gray-900/50 dark:bg-opacity-25 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <x-jet-button class="ml-4">
                    {{ __('Save') }}
                </x-jet-button>
            </div>
        </form>
    </div>
</div>
