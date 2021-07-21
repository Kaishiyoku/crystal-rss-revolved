<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        {{ trans_choice('unread_articles', $totalUnreadFeedItems) }}
    </x-slot>

    <livewire:feed-list/>
</x-app-layout>
