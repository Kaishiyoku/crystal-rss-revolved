<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Add feed') }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Add feed') }}
        </h2>
    </x-slot>

    {{ html()->modelForm($feed, 'post', route('feeds.store'))->class('px-4 sm:px-0')->open() }}
        @include('feed._form_fields')

        <x-button>{{ __('Save') }}</x-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
