<div x-data="feedList()">
    <template x-if="unreadFeedItems.length > 0">
        <div class="mb-8 lg:flex lg:justify-between lg:space-x-2 space-y-2 lg:space-y-0 px-4 sm:px-0">
            <div class="flex">
                <x-jet-dropdown align="left" width="60">
                    <x-slot name="trigger">
                        <span class="inline-flex rounded-md">
                            <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-left text-sm leading-4 font-medium rounded-md text-gray-500 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-700 dark:hover:text-gray-400 focus:outline-none focus:bg-gray-50 dark:focus:text-gray-400 dark:focus:bg-gray-600 dark:active:text-gray-300 active:bg-gray-50 dark:active:bg-gray-500 transition">
                                <span x-text="getFeedFilterDropdownButtonTitle()"></span>

                                <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </span>
                    </x-slot>

                    <x-slot name="content">
                        <div class="w-60 max-h-72 overflow-hidden overflow-y-auto">
                            <button
                                type="button"
                                @click="filterByFeed(null)"
                                class="flex justify-between items-center w-full text-left px-4 py-2 text-sm leading-5 focus:outline-none dark:focus:text-gray-300 transition"
                                :class="{'text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-600': filteredFeedId !== null, 'text-white bg-indigo-500 hover:bg-indigo-600 focus:bg-indigo-700': filteredFeedId === null}"
                                x-html="getAllFeedsButtonHtml()"
                            >
                            </button>

                            <div class="border-t border-gray-100 dark:border-gray-700"></div>

                            <template x-for="feed in feeds">
                                <button
                                    :key="feed.id"
                                    type="button"
                                    @click="filterByFeed(feed.id)"
                                    class="flex justify-between items-center w-full text-left px-4 py-2 text-sm leading-5 focus:outline-none dark:focus:text-gray-300 transition"
                                    :class="{'text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-100 dark:focus:bg-gray-600': feed.id !== filteredFeedId, 'text-white bg-indigo-500 hover:bg-indigo-600 focus:bg-indigo-700': feed.id === filteredFeedId}"
                                    x-html="getFeedFilterHtmlForFeed(feed)"
                                >
                                </button>
                            </template>
                        </div>
                    </x-slot>
                </x-jet-dropdown>
            </div>

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

            <x-card.card class="flex-grow md:rounded-none mb-16 md:mb-0" ::class="{'md:rounded-t-lg': index === 0, 'md:rounded-b-lg': index === unreadFeedItems.length - 1}">
                <div :class="{'opacity-40': isRead(unreadFeedItem.id)}">
                    <a
                        class="group block md:flex md:items-center md:space-x-4 md:px-4 md:py-3 transition ease-out duration-300 hover:bg-indigo-500 focus:outline-none focus:text-white focus:bg-indigo-600 transition"
                        :href="unreadFeedItem.url"
                    >
                        <div class="md:flex-shrink-0 md:block md:w-12">
                            <template x-if="unreadFeedItem.has_image">
                                <img
                                    :src="unreadFeedItem.image_url"
                                    :alt="unreadFeedItem.title"
                                    class="object-cover w-full md:w-auto h-72 md:h-auto md:rounded"
                                />
                            </template>
                            <template x-if="!unreadFeedItem.has_image">
                                <x-heroicon-s-photograph class="fill-current text-white bg-gray-300 w-full md:w-auto h-72 md:h-auto md:rounded"/>
                            </template>
                        </div>
                        <div class="w-full px-4 py-3 md:px-0 md:py-0">
                            <div class="group-hover:text-white text-2xl md:text-base" x-text="unreadFeedItem.title"></div>
                            <div class="group-hover:text-gray-300 w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-xs pt-2 md:pt-0">
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
                                <div class="group-hover:text-gray-300 group-focus:text-gray-200 pt-1 text-muted md:text-xs" x-text="unreadFeedItem.description">w</div>
                            </template>
                        </div>
                    </a>

                    <div class="md:hidden px-4 pb-4 flex justify-end">
                        <button
                            type="button"
                            class="mt-4 disabled:cursor-not-allowed disabled:opacity-100 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition"
                            :disabled="isLoading(unreadFeedItem.id)"
                            @click.prevent="toggleMarkAsRead(unreadFeedItem.id)"
                        >
                            <template x-if="isLoading(unreadFeedItem.id)">
                                <x-icon.loading/>
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
        <x-secondary-button type="button" class="mx-4 sm:mx-0 mt-8" ::disabled="isMoreLoading" @click="loadMore()" x-cloak>
            <template x-if="isMoreLoading">
                <x-icon.loading class="mr-2"/>
            </template>

            {{ __('Load more') }}
        </x-secondary-button>
    </template>
</div>

<script type="text/javascript">
    function feedList() {
        return {
            init() {
                this.loadMore();
            },
            feeds: @json($feeds),
            unreadFeedItems: [],
            filteredFeedId: null,
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
            getFeedFilterDropdownButtonTitle() {
                if (this.filteredFeedId) {
                    return '{{ __('feed_filter') }}'.replace(':name', this.feeds.find((feed) => feed.id === this.filteredFeedId).name);
                }

                return '{{ __('Filter by feed') }}';
            },
            getAllFeedsButtonHtml() {
                const totalNumberOfFeedItems = this.feeds.reduce((carry, feed) => carry + feed.unread_feed_items_count, 0);

                return `<div>{{ __('All feeds') }}</div><div class="${!this.filteredFeedId ? '' : 'text-muted'} text-xs">(${totalNumberOfFeedItems})</div>`
            },
            getFeedFilterHtmlForFeed(feed) {
                return `<div>${feed.name}</div><div class="${this.filteredFeedId === feed.id ? '' : 'text-muted'} text-xs">(${feed.unread_feed_items_count})</div>`;
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
            filterByFeed(feedId) {
                axios.post('{{ route('feed_items.load') }}', {
                    numberOfDisplayedFeedItems: this.unreadFeedItems.length,
                    filteredFeedId: feedId,
                    offset: 0,
                    feedItemsPerPage: this.feedItemsPerPage,
                    readFeedItemIds: [],
                }).then(({data: {newOffset, hasMoreUnreadFeedItems, newUnreadFeedItems}}) => {
                    this.offset = newOffset;
                    this.hasMoreUnreadFeedItems = hasMoreUnreadFeedItems;
                    this.unreadFeedItems = newUnreadFeedItems;
                    this.filteredFeedId = feedId;
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
