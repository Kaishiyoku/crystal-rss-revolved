<x-app-layout>
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

            <x-delete-button :url="route('feeds.destroy', $feed)"/>
        </div>
    </x-slot>

    {{ html()->modelForm($feed, 'put', route('feeds.update', $feed))->class('px-4 sm:px-0')->open() }}
        @include('feed._form_fields')

        <x-button>{{ __('Save') }}</x-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
