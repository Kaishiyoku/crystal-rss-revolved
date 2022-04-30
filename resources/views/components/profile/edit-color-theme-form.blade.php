<x-jet-action-section>
    <x-slot name="title">
        {{ __('Edit color theme') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Select your preferred color theme.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-500">
            {{ __('theme.currently_selected', ['theme' => __(Auth::user()->theme->key)]) }}
        </div>

        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0 mt-5">
            <x-button.secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Base)" class="w-full sm:w-auto py-3 sm:py-2 bg-[#d1d8f9] dark:bg-[#31257a]">
                {{ __(getTranslationForColorTheme(\App\Enums\ColorTheme::Base())) }}
            </x-button.secondary-update-button>

            <x-button.secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Lavender)" class="w-full sm:w-auto py-3 sm:py-2 bg-[#b7abe3] dark:bg-[#31257a]">
                {{ __(getTranslationForColorTheme(\App\Enums\ColorTheme::Lavender())) }}
            </x-button.secondary-update-button>

            <x-button.secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::MagicViolet)" class="w-full sm:w-auto py-3 sm:py-2 bg-[#8400FA] dark:bg-[#48008A]">
                {{ __(getTranslationForColorTheme(\App\Enums\ColorTheme::MagicViolet())) }}
            </x-button.secondary-update-button>
        </div>
    </x-slot>
</x-jet-action-section>
