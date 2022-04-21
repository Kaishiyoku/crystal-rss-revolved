<x-jet-action-section>
    <x-slot name="title">
        {{ __('Edit color theme') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Select your preferred color theme.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-500">
            {{ __('theme.currently_selected', ['theme' => __(\App\Enums\ColorTheme::fromValue(Auth::user()->theme)->key)]) }}
        </div>

        <div class="flex space-x-4 mt-5">
            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Base)" class="bg-[#d1d8f9] dark:bg-[#31257a]">
                {{ __(\App\Enums\ColorTheme::Base()->key) }}
            </x-secondary-update-button>

            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Lavender)" class="bg-[#b7abe3] dark:bg-[#31257a]">
                {{ __(\App\Enums\ColorTheme::Lavender()->key) }}
            </x-secondary-update-button>
        </div>
    </x-slot>
</x-jet-action-section>
