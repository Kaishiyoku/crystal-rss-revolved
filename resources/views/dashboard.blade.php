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

            <x-button.secondary-update-button :url="route('feeds.mark_all_as_read')">
                {{ __('Mark all as read') }}
            </x-button.secondary-update-button>
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

    <div x-data="feedList()">
        @foreach ($unreadFeedItems as $unreadFeedItem)
            <div class="md:flex md:items-center md:space-x-4">
                <button
                    type="button"
                    class="hidden md:inline-flex my-2 disabled:cursor-not-allowed disabled:opacity-100 items-center p-3 text-gray-800 dark:text-gray-500 rounded-full font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-gray-700 active:bg-gray-900 dark:active:bg-gray-600 focus:outline-none focus:border-gray-900 dark:focus:border-gray-400 focus:ring ring-gray-300 dark:ring-gray-500 disabled:opacity-25 transition ease-out duration-300"
                    :disabled="isLoading(@json($unreadFeedItem->id))"
                    @click.prevent="toggleMarkAsRead(@json($unreadFeedItem->id))"
                >
                    <template x-if="isLoading(@json($unreadFeedItem->id))">
                        <x-icon.loading/>
                    </template>
                    <template x-if="!isLoading(@json($unreadFeedItem->id)) && !isRead(@json($unreadFeedItem->id))">
                        <x-heroicon-s-eye class="w-5 h-5"/>
                    </template>
                    <template x-if="!isLoading(@json($unreadFeedItem->id)) && isRead(@json($unreadFeedItem->id))">
                        <x-heroicon-s-eye-off class="w-5 h-5"/>
                    </template>
                </button>

                <x-card.card class="{{ classNames('grow md:rounded-none mb-16 md:mb-0 overflow-hidden', ['md:rounded-t-lg' => $loop->index === 0, 'md:rounded-b-lg' => $loop->index === $unreadFeedItems->count() - 1]) }}">
                    <div class="transition duration-200" :class="{'opacity-40': isRead(@json($unreadFeedItem->id))}">
                        <a
                            class="group block md:flex md:items-center md:space-x-4 md:px-4 md:py-3 transition ease-out duration-300 hover:bg-primary-500 focus:outline-none focus:text-white focus:bg-primary-600 transition"
                            href="{{ $unreadFeedItem->url }}"
                        >
                            <div class="md:shrink-0 md:block md:w-16">
                                @if ($unreadFeedItem->has_image)
                                    <img
                                        src="{{ $unreadFeedItem->image_url }}"
                                        alt="{{ $unreadFeedItem->title }}"
                                        class="object-cover w-full md:w-auto h-72 md:h-auto md:rounded"
                                        loading="lazy"
                                    />
                                @else
                                    <x-heroicon-s-photograph class="fill-current text-white dark:text-gray-400 bg-gray-300 dark:bg-gray-700 w-full md:w-auto h-72 md:h-auto md:rounded"/>
                                @endif
                            </div>
                            <div class="w-full px-4 py-3 md:px-0 md:py-0">
                                <div class="group-hover:text-white text-2xl md:text-lg">{{ $unreadFeedItem->title }}</div>
                                <div class="group-hover:text-gray-300 w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-sm pt-2 md:pt-0">
                                    <div class="flex items-center">
                                        @if ($unreadFeedItem->feed->favicon_url)
                                            <img src="{{ $unreadFeedItem->feed->favicon_url }}" class="w-4 h-4" alt="Favicon"/>
                                        @else
                                            <x-heroicon-o-photograph class="h-4 w-4"/>
                                        @endif

                                        <div class="ml-1">{{ $unreadFeedItem->feed->name }}</div>
                                    </div>
                                    <div>{{ $unreadFeedItem->formatted_posted_at }}</div>
                                </div>

                                @if (Auth::user()->is_feed_item_description_visible && $unreadFeedItem->description)
                                    <div class="group-hover:text-gray-300 group-focus:text-gray-200 pt-1 text-muted md:text-sm">{{ $unreadFeedItem->description }}</div>
                                @endif
                            </div>
                        </a>

                        <div class="md:hidden mt-4 px-4 pb-4">
                            <button
                                type="button"
                                class="w-full inline-flex items-center px-4 py-4 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-400 uppercase tracking-widest shadow-sm hover:text-gray-500 dark:hover:text-gray-300 focus:outline-none focus:border-primary-300 dark:focus:border-primary-500 focus:ring focus:ring-primary-200 dark:focus:ring-primary-600 active:text-gray-800 dark:active:text-gray-200 active:bg-gray-50 dark:active:bg-gray-600 disabled:opacity-25 transition"
                                :disabled="isLoading(@json($unreadFeedItem->id))"
                                @click.prevent="toggleMarkAsRead(@json($unreadFeedItem->id))"
                            >
                                <template x-if="isLoading(@json($unreadFeedItem->id))">
                                    <x-icon.loading class="mr-2"/>
                                </template>
                                <template x-if="!isLoading(@json($unreadFeedItem->id)) && !isRead(@json($unreadFeedItem->id))">
                                    <x-heroicon-s-eye class="w-5 h-5 mr-2"/>
                                </template>
                                <template x-if="!isLoading(@json($unreadFeedItem->id)) && isRead(@json($unreadFeedItem->id))">
                                    <x-heroicon-s-eye-off class="w-5 h-5 mr-2"/>
                                </template>

                                <span>{{ __('Mark as read') }}</span>
                            </button>
                        </div>
                    </div>
                </x-card.card>
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
