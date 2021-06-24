<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add feed') }}
        </h2>
    </x-slot>

    {{ html()->modelForm($feed, 'post', route('feeds.store'))->class('px-4 sm:px-0')->open() }}
        @include('feed._form_fields')

        <x-button>{{ __('Save') }}</x-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
