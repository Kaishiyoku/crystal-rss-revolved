<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Edit feed') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <x-site.heading>
                    {{ __('Edit feed') }}
                </x-site.heading>

                <div>
                    {{ $feed->name }}
                </div>
            </div>

            <x-delete-button :url="route('feeds.destroy', $feed)"/>
        </div>
    </x-slot>

    {{ html()->modelForm($feed, 'put', route('feeds.update', $feed))->class('px-4 sm:px-0')->open() }}
        @include('feed._form_fields')

        <x-jet-button>{{ __('Save') }}</x-jet-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
