<x-app-layout>
    <x-slot name="pageTitle">
        {{ __('Dashboard') }}
    </x-slot>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center space-y-2 sm:space-y-0">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-300 leading-tight">
                    {{ __('Dashboard') }}
                </h2>

                <div>{{ trans_choice('unread_articles', $totalUnreadFeedItems) }}</div>
            </div>

            @if ($unreadFeedItems->isNotEmpty())
                <x-button.secondary-update-button :url="route('feeds.mark_all_as_read')">
                    {{ __('Mark all as read') }}
                </x-button.secondary-update-button>
            @endif
        </div>
    </x-slot>

    @if ($unreadFeedItems->isNotEmpty())
        <div class="w-full md:w-[400px] mb-8 px-4 sm:px-0">
            <x-dropdown.select-dropdown
                :placeholder="__('Filter by feed...')"
                :value="optional($selectedFeed)->name"
                :autocomplete-values="$feedOptions"
                width="96"
                trigger-class="py-3"
                mobile-full-width
            />
        </div>
    @endif

    @if ($newlyFetchedFeedItemCount > 0)
        <div class="alert alert--info mb-8">
            <div class="mb-4">
                {{ trans_choice('new_items_found', $newlyFetchedFeedItemCount) }}
            </div>

            <a href="{{ route('dashboard') }}" class="alert--cta">
                {{ __('Refresh now') }}
            </a>
        </div>
    @endif

    <div x-data="feedList()" class="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-16 sm:gap-y-4">
        @foreach ($unreadFeedItems as $unreadFeedItem)
            <div class="flex flex-col">
                <x-feed-item :feed-item="$unreadFeedItem"/>
            </div>
        @endforeach
    </div>

    @if ($unreadFeedItems->count() < $totalUnreadFeedItemCount)
        <div class="md:flex md:justify-between md:items-center px-4 md:px-0 mt-8">
            <x-button.primary-button-link class="sm:mx-0 py-4 md:py-2 w-full md:w-auto" :url="route('dashboard', [$selectedFeed ? $selectedFeed->id : \App\Enums\FeedFilter::All, $unreadFeedItems->first()->checksum, $unreadFeedItems->last()->checksum])">
                {{ __('Load more') }}
            </x-button.primary-button-link>

            <button
                type="button"
                class="text-primary-500 dark:text-secondary-500 opacity-50 hover:opacity-75 focus:opacity-90 transition mt-8 md:mt-0"
                data-scroll-to-top
            >
                <x-heroicon-s-arrow-circle-up class="w-16 h-16"/>
            </button>
        </div>
    @endif

    @push('scripts')
        <script type="text/javascript">
            function feedList() {
                return {
                    readFeedItemIds: [],
                    loadingList: [],
                    confirm(event) {
                        if (!confirm('{{ __('Are you sure?') }}')) {
                            event.preventDefault()
                        }
                    },
                    isRead(feedItemId) {
                        return this.readFeedItemIds.includes(feedItemId);
                    },
                    addIsRead(feedItemId) {
                        this.readFeedItemIds.push(feedItemId);
                    },
                    removeIsRead(feedItemId) {
                        this.readFeedItemIds = this.readFeedItemIds.filter((id) => id !== feedItemId);
                    },
                    isLoading(feedItemId) {
                        return this.loadingList.includes(feedItemId);
                    },
                    addIsLoading(feedItemId) {
                        this.loadingList.push(feedItemId);
                    },
                    removeIsLoading(feedItemId) {
                        this.loadingList = this.loadingList.filter((id) => id !== feedItemId);
                    },
                    toggleMarkAsRead(feedItemId) {
                        this.addIsLoading(feedItemId);

                        const baseUrl = '{{ route('feed_items.toggle_mark_as_read', ':feedItemId') }}';

                        axios.put(baseUrl.replace(':feedItemId', feedItemId)).then(({data}) => {
                            const isRead = !!data.read_at;

                            this.removeIsLoading(feedItemId);

                            if (isRead) {
                                this.addIsRead(feedItemId);
                            } else {
                                this.removeIsRead(feedItemId);
                            }
                        });
                    },
                };
            }
        </script>
    @endpush
</x-app-layout>
