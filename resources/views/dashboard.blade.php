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

    @if (config('app.enable_push_notifications'))
        @push('scripts')
            <script type="text/javascript">
                onDomReady(() => {
                    Echo.private(`feed-list.${userId}`)
                        .listen('NewFeedItemsFetched', ({title, message}) => {
                            Alpine.store('toasts').add(message, 10000);
                            sendPushNotification(title, message, 10000, () => window.location.reload());
                        });
                });
            </script>
        @endpush
    @endif
</x-app-layout>
