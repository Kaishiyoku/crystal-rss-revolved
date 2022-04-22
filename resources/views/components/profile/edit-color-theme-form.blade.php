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

        <div class="flex space-x-4 mt-5">
            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Base)" class="bg-[#d1d8f9] dark:bg-[#31257a]">
                {{ __(getTranslationForColorTheme(\App\Enums\ColorTheme::Base())) }}
            </x-secondary-update-button>

            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Lavender)" class="bg-[#b7abe3] dark:bg-[#31257a]">
                {{ __(getTranslationForColorTheme(\App\Enums\ColorTheme::Lavender())) }}
            </x-secondary-update-button>

            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Solarized)" class="bg-[#6B5824] dark:bg-[#413516]">
                {{ __(getTranslationForColorTheme(\App\Enums\ColorTheme::Solarized())) }}
            </x-secondary-update-button>

            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::MagicViolet)" class="bg-[#8400FA] dark:bg-[#48008A]">
                {{ __(getTranslationForColorTheme(\App\Enums\ColorTheme::MagicViolet())) }}
            </x-secondary-update-button>
        </div>
    </x-slot>
</x-jet-action-section>
