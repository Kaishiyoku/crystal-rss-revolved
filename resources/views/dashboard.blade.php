<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        <div>{{ trans_choice('unread_articles', $totalUnreadFeedItems) }}</div>
    </x-slot>

    <livewire:feed-list/>
</x-app-layout>
