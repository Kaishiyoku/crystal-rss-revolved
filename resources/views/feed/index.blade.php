<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Feeds') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
                    {{ __('Feeds') }}
                </h2>

                <div>{{ trans_choice('total_number_of_feeds', $feeds->count()) }}</div>
            </div>

            <x-button.secondary-button-link :url="route('feeds.create')">
                {{ __('Add') }}
            </x-button.secondary-button-link>
        </div>
    </x-slot>

    <x-card.card class="divide-y divide-gray-200 dark:divide-gray-700">
        @foreach ($feeds as $feed)
            <a class="group block md:flex md:items-center md:justify-between md:space-x-4 px-4 py-3 transition first:rounded-t-md last:rounded-b-md hover:bg-gray-50 dark:hover:bg-gray-700 dark:hover:text-gray-300" href="{{ route('feeds.edit', $feed) }}">
                <div class="flex">
                    @if ($feed->favicon_url)
                        <img src="{{ $feed->favicon_url }}" alt="Favicon" class="w-5 h-5"/>
                    @else
                        <x-heroicon-o-photograph class="h-6 w-6"/>
                    @endif

                    <div class="ml-2">{{ $feed->name }}</div>
                </div>
                <div class="dark:group-hover:text-gray-400 pt-2 md:pt-0 md:text-right text-sm text-muted">
                    <div>{{ __('category_name', ['name' => $feed->category->getName()]) }}</div>
                    <div>{{ trans_choice('number_of_recently_fetched_feed_items', $feed->feed_items_count) }}</div>
                </div>
            </a>
        @endforeach
    </x-card.card>
</x-app-layout>
