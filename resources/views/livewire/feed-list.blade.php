<div>
    @foreach ($unreadFeedItems as $i => $unreadFeedItem)
        <div wire:ignore x-data="{isLoading: false, isRead: {{ json_encode(!!$unreadFeedItem->read_at) }}}" class="md:flex md:items-center md:space-x-4">
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

            <x-card.card class="{{ classNames('flex-grow sm:rounded-none mb-12 md:mb-0', ['sm:rounded-t-lg' => $i === 0, 'sm:rounded-b-lg' => $i === $unreadFeedItems->count() - 1]) }}">
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
                    <div class="px-4 py-3 md:px-0 md:py-0">
                        <div class="group-hover:text-white text-2xl md:text-base">{{ $unreadFeedItem->title }}</div>
                        <div class="group-hover:text-gray-300 group-focus:text-gray-200 md:flex md:space-x-2 text-gray-500 md:text-xs pt-2 md:pt-0">
                            <div>{{ $unreadFeedItem->posted_at->format(__('date.datetime')) }}</div>
                            <div>{{ $unreadFeedItem->feed->name }}</div>
                        </div>

                        <div class="md:hidden flex justify-end">
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
                    </div>
                </a>
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
