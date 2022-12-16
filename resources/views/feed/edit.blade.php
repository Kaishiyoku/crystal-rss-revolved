<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Edit feed') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
                    {{ __('Edit feed') }}
                </h2>

                <div>
                    {{ $feed->name }}
                </div>
            </div>

            <x-button.button danger confirm :action="route('feeds.destroy', $feed)" method="delete">
                {{ __('Delete') }}
            </x-button.button>
        </div>
    </x-slot>

    {{ html()->modelForm($feed, 'put', route('feeds.update', $feed))->class('px-4 sm:px-0')->open() }}
        @include('feed._form_fields')

        <x-jet-button>{{ __('Save') }}</x-jet-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
