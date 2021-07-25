<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        <div>{{ trans_choice('unread_articles', $totalUnreadFeedItems) }}</div>
    </x-slot>

    <x-feed-list/>
</x-app-layout>
