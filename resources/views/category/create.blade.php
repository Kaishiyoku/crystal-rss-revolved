<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Add category') }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Add category') }}
        </h2>
    </x-slot>

    {{ html()->modelForm($category, 'post', route('categories.store'))->class('px-4 sm:px-0')->open() }}
        @include('category._form_fields')

        <x-jet-button>{{ __('Save') }}</x-jet-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
