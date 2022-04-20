<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Categories') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <x-site.heading>
                    {{ __('Categories') }}
                </x-site.heading>

                <div>{{ trans_choice('total_number_of_categories', $categories->count()) }}</div>
            </div>

            <x-secondary-button-link :url="route('categories.create')">
                {{ __('Add') }}
            </x-secondary-button-link>
        </div>
    </x-slot>

    <x-card.card class="divide-y divide-gray-500/20 dark:divide-gray-700">
        @foreach ($categories as $category)
            <a class="group block md:flex md:justify-between md:space-x-4 px-4 py-3 transition first:rounded-t-md last:rounded-b-md hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-gray-500/30" href="{{ route('categories.edit', $category) }}">
                <div>{{ $category->getName() }}</div>
                <div class="dark:group-hover:text-gray-500/75 text-muted">{{ trans_choice('number_of_feeds', $category->feeds_count) }}</div>
            </a>
        @endforeach
    </x-card.card>
</x-app-layout>
