<div>
    @if ($unreadFeedItems->isNotEmpty())
        <div class="mb-8 inline-block" x-data="{dropdownButtonTitle: '{{ __('Filter by feed') }}'}">
            <x-jet-dropdown align="left" width="60">
                <x-slot name="trigger">
                    <span class="inline-flex rounded-md">
                        <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:bg-gray-50 hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition">
                            <span x-text="dropdownButtonTitle">{{ __('Filter by feed') }}</span>

                            <svg class="ml-2 -mr-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </span>
                </x-slot>

                <x-slot name="content">
                    <div class="w-60 max-h-72 overflow-hidden overflow-y-scroll">
                        <button
                            type="button"
                            @click="$wire.filterByFeed(null).then(() => dropdownButtonTitle = '{{ __('Filter by feed') }}')"
                            class="{{ classNames('block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 focus:outline-none transition', ['hover:bg-gray-100 focus:bg-gray-100' => $filterFeedId !== null, 'text-white bg-indigo-500 hover:bg-indigo-600 focus:bg-indigo-700' => $filterFeedId === null]) }}"
                        >
                            {{ __('All feeds') }}
                        </button>

                        <div class="border-t border-gray-100"></div>

                        @foreach ($feeds as $feed)
                            <button
                                type="button"
                                @click="$wire.filterByFeed({{ $feed->id }}).then(() => dropdownButtonTitle = '{{ __('feed_filter', ['name' => $feed->name]) }}')"
                                class="{{ classNames('block w-full text-left px-4 py-2 text-sm leading-5 text-gray-700 focus:outline-none transition', ['hover:bg-gray-100 focus:bg-gray-100' => $feed->id !== $filterFeedId, 'text-white bg-indigo-500 hover:bg-indigo-600 focus:bg-indigo-700' => $feed->id === $filterFeedId]) }}"
                            >
                                {{ $feed->name }}
                            </button>
                        @endforeach
                    </div>
                </x-slot>
            </x-jet-dropdown>
        </div>
    @endif

    @foreach ($unreadFeedItems as $i => $unreadFeedItem)
        <div x-data="{isLoading: false, isRead: @json(!!$unreadFeedItem->read_at)}" class="md:flex md:items-center md:space-x-4">
            <button
                type="button"
                class="hidden md:inline-flex my-2 disabled:cursor-not-allowed disabled:opacity-100 items-center p-3 text-gray-800 rounded-full font-semibold text-xs uppercase tracking-widest hover:text-white hover:bg-gray-700 active:bg-gray-900 focus:outline-none focus:border-gray-900 focus:ring ring-gray-300 disabled:opacity-25 transition ease-out duration-300"
                :disabled="isLoading"
                @click.prevent="isLoading = true; axios.put('{{ route('feed_items.toggle_mark_as_read', $unreadFeedItem) }}').then(({data}) => {isLoading = false; isRead = !!data.read_at; if (isRead) {readFeedIds.push(data.id)} else {readFeedIds = readFeedIds.filter((feedId) => feedId !== data.id)}})"
            >
                <svg x-show="isLoading" x-cloak class="animate-spin text-indigo-500 w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>

                <x-heroicon-s-eye x-show="!isLoading" class="w-5 h-5"/>
            </button>

            <x-card.card class="{{ classNames('flex-grow md:rounded-none mb-12 md:mb-0', ['md:rounded-t-lg' => $i === 0, 'md:rounded-b-lg' => $i === $unreadFeedItems->count() - 1]) }}">
                <a
                    class="group block md:flex md:items-center md:space-x-4 md:px-4 md:py-3 transition ease-out duration-300 hover:bg-indigo-500 focus:outline-none focus:text-white focus:bg-indigo-600 transition"
                    :class="{'opacity-[35%]': isRead}"
                    href="{{ $unreadFeedItem->url }}"
                >
                    <div class="md:flex-shrink-0 md:block md:w-12">

                        @if ($unreadFeedItem->hasImage())
                            <img
                                src="{{ $unreadFeedItem->image_url }}"
                                alt="{{ $unreadFeedItem->title }}"
                                class="object-cover w-full md:w-auto h-72 md:h-auto md:rounded"
                            />
                        @else
                            <svg class="fill-current text-white bg-gray-300 w-full md:w-auto h-72 md:h-auto md:rounded" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        @endif
                    </div>
                    <div class="w-full px-4 py-3 md:px-0 md:py-0">
                        <div class="group-hover:text-white text-2xl md:text-base">{{ $unreadFeedItem->title }}</div>
                        <div class="group-hover:text-gray-300 w-full group-focus:text-gray-200 md:flex md:justify-between md:space-x-2 text-muted md:text-xs pt-2 md:pt-0">
                            <div>{{ $unreadFeedItem->feed->name }}</div>
                            <div>{{ $unreadFeedItem->posted_at->format(__('date.datetime')) }}</div>
                        </div>
                    </div>
                </a>

                <div class="md:hidden px-4 pb-4 flex justify-end">
                    <button
                        type="button"
                        class="mt-4 disabled:cursor-not-allowed disabled:opacity-100 inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:text-gray-500 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 active:text-gray-800 active:bg-gray-50 transition"
                        :disabled="isLoading"
                        @click.prevent="isLoading = true; axios.put('{{ route('feed_items.toggle_mark_as_read', $unreadFeedItem) }}').then(({data}) => {isLoading = false; isRead = !!data.read_at; if (isRead) {readFeedIds.push(data.id)} else {readFeedIds = readFeedIds.filter((feedId) => feedId !== data.id)}})"
                    >
                        <svg x-show="isLoading" x-cloak class="animate-spin text-indigo-500 w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>

                        <x-heroicon-s-eye x-show="!isLoading" class="w-5 h-5 mr-2"/>

                        <span>{{ __('Mark as read') }}</span>
                    </button>
                </div>
            </x-card.card>
        </div>
    @endforeach

    @if ($hasMoreFeedItems)
        <x-secondary-button type="button" class="mt-8" wire:click="loadMore(readFeedIds)">
            {{ __('Load more') }}
        </x-secondary-button>
    @endif
</div>

<script type="text/javascript">
    let readFeedIds = [];
</script>
