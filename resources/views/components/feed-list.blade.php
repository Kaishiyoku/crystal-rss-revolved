<div x-data="feedList()">
    <template x-if="unreadFeedItems.length > 0">
        <div class="mb-8 lg:flex lg:justify-between lg:space-x-2 space-y-2 lg:space-y-0 px-4 sm:px-0">
            <x-secondary-update-button :url="route('feeds.mark_all_as_read')" @click="confirm($event)">
                {{ __('Mark all as read') }}
            </x-secondary-update-button>
        </div>
    </template>

    <template x-for="(unreadFeedItem, index) in unreadFeedItems">
        <div :key="unreadFeedItem.id" class="md:flex md:items-center md:space-x-4">
            <button
                type="button"
                class="hidden md:inline-flex my-2 disabled:cursor-not-allowed disabled:opacity-100 items-center p-3 text-gray-800 dark:text-gray-500 rounded-full font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-gray-700 active:bg-gray-900 dark:active:bg-gray-600 focus:outline-none focus:border-gray-900 dark:focus:border-gray-400 focus:ring ring-gray-300 dark:ring-gray-500 disabled:opacity-25 transition ease-out duration-300"
                :disabled="isLoading(unreadFeedItem.id)"
                @click.prevent="toggleMarkAsRead(unreadFeedItem.id)"
            >
                <template x-if="isLoading(unreadFeedItem.id)">
                    <x-icon.loading/>
                </template>
                <template x-if="!isLoading(unreadFeedItem.id)">
                    <x-heroicon-s-eye class="w-5 h-5"/>
                </template>
            </button>

            <x-card.card class="flex-grow md:rounded-none mb-16 md:mb-0 overflow-hidden" ::class="{'md:rounded-t-lg': index === 0, 'md:rounded-b-lg': index === unreadFeedItems.length - 1}">
                <div :class="{'opacity-40': isRead(unreadFeedItem.id)}">
                    <a
                        class="group block md:flex md:items-center md:space-x-4 md:px-4 md:py-3 transition ease-out duration-300 hover:bg-indigo-500 focus:outline-none focus:text-white focus:bg-indigo-600 transition"
                        :href="unreadFeedItem.url"
                    >
                        <div class="md:flex-shrink-0 md:block md:w-16">
                            <template x-if="unreadFeedItem.has_image">
                                <img
                                    :src="unreadFeedItem.image_url"
                                    :alt="unreadFeedItem.title"
                                    class="object-cover w-full md:w-auto h-72 md:h-auto md:rounded"
                                    loading="lazy"
                                />
                            </template>
                            <template x-if="!unreadFeedItem.has_image">
                                <x-heroicon-s-photograph class="fill-current text-white dark:text-gray-400 bg-gray-300 dark:bg-gray-700 w-full md:w-auto h-72 md:h-auto md:rounded"/>
                            </template>
                        </div>
                        <div class="w-full px-4 py-3 md:px-0 md:py-0">
                            <div class="group-hover:text-white text-2xl md:text-lg" x-text="unreadFeedItem.title"></div>
                            <div class="group-hover:text-gray-300 w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-sm pt-2 md:pt-0">
                                <div class="flex items-center">
                                    <template x-if="unreadFeedItem.feed.favicon_url">
                                        <img :src="unreadFeedItem.feed.favicon_url" class="w-4 h-4" alt="Favicon"/>
                                    </template>
                                    <template x-if="!unreadFeedItem.feed.favicon_url">
                                        <x-heroicon-o-photograph class="h-4 w-4"/>
                                    </template>
                                    <div class="ml-1" x-text="unreadFeedItem.feed.name"></div>
                                </div>
                                <div x-text="unreadFeedItem.formatted_posted_at"></div>
                            </div>
                            <template x-if="unreadFeedItem.description">
                                <div class="group-hover:text-gray-300 group-focus:text-gray-200 pt-1 text-muted md:text-sm" x-text="unreadFeedItem.description">w</div>
                            </template>
                        </div>
                    </a>

                    <div class="md:hidden mt-4 px-4 pb-4">
                        <button
                            type="button"
                            class="w-full inline-flex items-center px-4 py-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-400 uppercase tracking-widest shadow-sm hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:border-indigo-300 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-600 active:text-gray-800 dark:active:text-gray-200 active:bg-gray-50 dark:active:bg-gray-600 disabled:opacity-25 transition"
                            :disabled="isLoading(unreadFeedItem.id)"
                            @click.prevent="toggleMarkAsRead(unreadFeedItem.id)"
                        >
                            <template x-if="isLoading(unreadFeedItem.id)">
                                <x-icon.loading class="mr-2"/>
                            </template>
                            <template x-if="!isLoading(unreadFeedItem.id)">
                                <x-heroicon-s-eye class="w-5 h-5 mr-2"/>
                            </template>

                            <span>{{ __('Mark as read') }}</span>
                        </button>
                    </div>
                </div>
            </x-card.card>
        </div>
    </template>

    <template x-if="hasMoreUnreadFeedItems">
        <div class="mx-4">
            <x-secondary-button type="button" class="sm:mx-0 mt-8 py-4 md:py-2 w-full md:w-auto" ::disabled="isMoreLoading" @click="loadMore()" x-cloak>
                <template x-if="isMoreLoading">
                    <x-icon.loading class="mr-2"/>
                </template>

                {{ __('Load more') }}
            </x-secondary-button>
        </div>
    </template>
</div>

@push('scripts')
    <script type="text/javascript">
        function feedList() {
            return {
                init() {
                    this.loadMore();
                },
                unreadFeedItems: [],
                readFeedItemIds: [],
                feedItemsPerPage: {{ $feedItemsPerPage }},
                offset: 0,
                loadingList: [],
                hasMoreUnreadFeedItems: false,
                isMoreLoading: false,
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
                loadMore() {
                    this.isMoreLoading = true;

                    axios.post('{{ route('feed_items.load') }}', {
                        numberOfDisplayedFeedItems: this.unreadFeedItems.length,
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
@endpush
