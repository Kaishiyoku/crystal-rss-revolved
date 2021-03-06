<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Edit category') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
                    {{ __('Edit category') }}
                </h2>

                <div>
                    {{ $category->getName() }}
                </div>
            </div>

            <x-button.delete-button :url="route('categories.destroy', $category)"/>
        </div>
    </x-slot>

    {{ html()->modelForm($category, 'put', route('categories.update', $category))->class('px-4 sm:px-0')->open() }}
        @include('category._form_fields')

        <x-jet-button>{{ __('Save') }}</x-jet-button>
    {{ html()->closeModelForm() }}
</x-app-layout>
