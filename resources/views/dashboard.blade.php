<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Dashboard') }}
    </x-slot>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
            {{ __('Dashboard') }}
        </h2>

        <div>{{ trans_choice('unread_articles', $totalUnreadFeedItems) }}</div>
    </x-slot>

    <x-feed-list/>

    @push('scripts')
        <script type="text/javascript">
            onDomReady(() => {
                Echo.private(`feed-list.${userId}`)
                    .listen('NewFeedItemsFetched', (data) => {
                        Alpine.store('toasts').add(data.message, 5000);
                    });
            });
        </script>
    @endpush
</x-app-layout>
