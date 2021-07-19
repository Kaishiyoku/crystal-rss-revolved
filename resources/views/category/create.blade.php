<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Add category') }}
        </h2>
    </x-slot>

    {{ html()->modelForm($category, 'post', route('categories.store'))->open() }}
        @include('category._form_fields')

        <x-button>{{ __('Save') }}</x-button>
    {{ html()->closeModelForm() }}
</x-app-layout>