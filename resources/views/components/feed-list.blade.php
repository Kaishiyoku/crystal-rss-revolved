<div x-data="feedList()">
    <template x-for="(unreadFeedItem, index) in unreadFeedItems">
        <div :key="unreadFeedItem.id" class="md:flex md:items-center md:space-x-4">
            <button
                type="button"
                class="hidden md:inline-flex my-2 disabled:cursor-not-allowed disabled:opacity-100 items-center p-3 text-gray-800 dark:text-gray-500 rounded-full font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-gray-700 active:bg-gray-900 dark:active:bg-gray-600 focus:outline-none focus:border-gray-900 dark:focus:border-gray-400 focus:ring ring-gray-300 dark:ring-gray-500 disabled:opacity-25 transition ease-out duration-300"
            >
                <button
                    type="button"
                    class="hidden md:inline-flex my-2 disabled:cursor-not-allowed disabled:opacity-100 items-center p-3 text-gray-800 dark:text-gray-500 rounded-full font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-gray-700 active:bg-gray-900 dark:active:bg-gray-600 focus:outline-none focus:border-gray-900 dark:focus:border-gray-400 focus:ring ring-gray-300 dark:ring-gray-500 disabled:opacity-25 transition ease-out duration-300"
                    :disabled="isLoading(unreadFeedItem.id)"
                    @click.prevent="toggleMarkAsRead(unreadFeedItem.id)"
                >
                    <x-icon.loading x-show="isLoading(unreadFeedItem.id)"/>

                    <x-heroicon-s-eye x-show="!isLoading(unreadFeedItem.id)" class="w-5 h-5"/>
                </button>
            </button>

            <x-card.card class="flex-grow md:rounded-none mb-16 md:mb-0" ::class="{'md:rounded-t-lg': index === 0, 'md:rounded-b-lg': index === unreadFeedItems.length - 1}">
                <div :class="{'opacity-40': isRead(unreadFeedItem.id)}">
                    <a
                        class="group block md:flex md:items-center md:space-x-4 md:px-4 md:py-3 transition ease-out duration-300 hover:bg-indigo-500 focus:outline-none focus:text-white focus:bg-indigo-600 transition"
                        :href="unreadFeedItem.url"
                    >
                        <div class="md:flex-shrink-0 md:block md:w-12">
                            <img
                                :src="unreadFeedItem.image_url"
                                :alt="unreadFeedItem.title"
                                class="object-cover w-full md:w-auto h-72 md:h-auto md:rounded"
                                x-show="unreadFeedItem.image_url"
                            />

                            <svg class="fill-current text-white bg-gray-300 w-full md:w-auto h-72 md:h-auto md:rounded" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" x-show="!unreadFeedItem.image_url">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="w-full px-4 py-3 md:px-0 md:py-0">
                            <div class="group-hover:text-white text-2xl md:text-base" x-text="unreadFeedItem.title"></div>
                            <div class="group-hover:text-gray-300 w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-xs pt-2 md:pt-0">
                                <div x-text="unreadFeedItem.feed.name"></div>
                                <div x-text="unreadFeedItem.posted_at"></div>
                            </div>
                            <div class="group-hover:text-gray-300 group-focus:text-gray-200 pt-1 text-muted md:text-xs" x-show="unreadFeedItem.description" x-text="unreadFeedItem.description">w</div>
                        </div>
                    </a>

                    <div class="md:hidden px-4 pb-4 flex justify-end">
                        <button
                            type="button"
                            class="mt-4 disabled:cursor-not-allowed disabled:opacity-100 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition"
                            :disabled="isLoading(unreadFeedItem.id)"
                            @click.prevent="toggleMarkAsRead(unreadFeedItem.id)"
                        >
                            <x-icon.loading x-show="isLoading(unreadFeedItem.id)"/>

                            <x-heroicon-s-eye x-show="!isLoading(unreadFeedItem.id)" class="w-5 h-5 mr-2"/>

                            <span>{{ __('Mark as read') }}</span>
                        </button>
                    </div>
                </div>
            </x-card.card>
        </div>
    </template>

    <x-secondary-button type="button" class="mt-8" ::disabled="isMoreLoading" @click="loadMore()" x-cloak>
        <x-icon.loading class="mr-2" x-show="isMoreLoading"/>

        {{ __('Load more') }}
    </x-secondary-button>
</div>

<script type="text/javascript">
    function feedList() {
        return {
            init() {
                this.loadMore();
            },
            unreadFeedItems: [],
            filteredFeedId: null,
            readFeedItemIds: [],
            feedItemsPerPage: {{ $feedItemsPerPage }},
            offset: 0,
            loadingList: [],
            hasMoreUnreadFeedItems: true,
            isMoreLoading: false,
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
            loadMore() {
                this.isMoreLoading = true;

                axios.post('{{ route('feed_items.load') }}', {
                    numberOfDisplayedFeedItems: this.unreadFeedItems.length,
                    filteredFeedId: this.filteredFeedId,
                    offset: this.offset,
                    feedItemsPerPage: this.feedItemsPerPage,
                    readFeedItemIds: this.readFeedItemIds,
                }).then(({data: {newOffset, hasMoreUnreadFeedItems, newUnreadFeedItems}}) => {
                    const newUnreadFeedItemsWithFilteredOutDuplicates = newUnreadFeedItems
                        .filter((newUnreadFeedItem) => !this.unreadFeedItems
                            .map((unreadFeedItem) => unreadFeedItem.id)
                            .includes(newUnreadFeedItem.id)
                        );

                    this.offset = newOffset;
                    this.hasMoreUnreadFeedItems = hasMoreUnreadFeedItems;
                    this.unreadFeedItems = this.unreadFeedItems.concat(newUnreadFeedItemsWithFilteredOutDuplicates);
                }).finally(() => {
                    this.isMoreLoading = false;
                });
            },
        };
    }
</script>
