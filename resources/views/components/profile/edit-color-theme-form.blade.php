<x-jet-action-section>
    <x-slot name="title">
        {{ __('Edit color theme') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Select your preferred color theme.') }}
    </x-slot>

    <x-slot name="content">
        <div class="flex space-x-4">
            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Base)" class="dark:bg-[#31257a] dark:border-[#4837a9] dark:hover:bg-[#4837a9]">
                {{ __(\App\Enums\ColorTheme::Base()->key) }}
            </x-secondary-update-button>

            <x-secondary-update-button :url="route('user-edit-color-theme', \App\Enums\ColorTheme::Lavender)" class="dark:bg-[#2F2A41] dark:border-[#484063] dark:hover:bg-[#484063]">
                {{ __(\App\Enums\ColorTheme::Lavender()->key) }}
            </x-secondary-update-button>
        </div>
    </x-slot>
</x-jet-action-section>
