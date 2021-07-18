<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Categories') }}
            </h2>

            <x-secondary-button-link :url="route('categories.create')">
                {{ __('Add') }}
            </x-secondary-button-link>
        </div>
    </x-slot>

    <x-card.card class="divide-y divide-gray-100">
        @foreach ($categories as $category)
            <a class="flex items-center space-x-4 px-4 py-3 transition first:rounded-t-md last:rounded-b-md hover:bg-gray-50 focus:outline-none focus:bg-gray-50" href="{{ route('categories.edit', $category) }}">
                {{ $category->getName() }}
            </a>
        @endforeach
    </x-card.card>
</x-app-layout>
