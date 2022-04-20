<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Add category') }}
    </x-slot>

    <x-slot name="header">
        <x-site.heading>
            {{ __('Add category') }}
        </x-site.heading>
    </x-slot>

    {{ html()->modelForm($category, 'post', route('categories.store'))->class('px-4 sm:px-0')->open() }}
        @include('category._form_fields')

        <x-jet-button>{{ __('Save') }}</x-jet-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
