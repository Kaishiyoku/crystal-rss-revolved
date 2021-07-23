<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
                {{ __('Categories') }}
            </h2>

            <x-secondary-button-link :url="route('categories.create')">
                {{ __('Add') }}
            </x-secondary-button-link>
        </div>
    </x-slot>

    <x-card.card class="divide-y divide-gray-100 dark:divide-gray-700">
        @foreach ($categories as $category)
            <a class="group flex justify-between space-x-4 px-4 py-3 transition first:rounded-t-md last:rounded-b-md hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="{{ route('categories.edit', $category) }}">
                <div>{{ $category->getName() }}</div>
                <div class="dark:group-hover:text-gray-400 text-muted">{{ trans_choice('number_of_feeds', $category->feeds_count) }}</div>
            </a>
        @endforeach
    </x-card.card>
</x-app-layout>
