<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Add feed') }}
    </x-slot>

    <x-slot name="header">
        <x-site.heading>
            {{ __('Add feed') }}
        </x-site.heading>
    </x-slot>

    {{ html()->modelForm($feed, 'post', route('feeds.store'))->class('px-4 sm:px-0')->open() }}
        @include('feed._form_fields')

        <x-jet-button>{{ __('Save') }}</x-jet-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
