<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <x-card.card class="divide-y divide-gray-100">
        @foreach ($unreadFeedItems as $unreadFeedItem)
            <a
                x-data="{isRead: {{ json_encode(!!$unreadFeedItem->read_at) }}}"
                class="flex items-center space-x-4 px-4 py-3 transition first:rounded-t-md last:rounded-b-md hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition"
                :class="{'opacity-50': isRead}"
                href="{{ $unreadFeedItem->url }}"
            >
                <div>
                    <x-secondary-button
                        type="button"
                        @click.prevent="axios.put('{{ route('feed_items.toggle_mark_as_read', $unreadFeedItem) }}').then(({data}) => isRead = !!data.read_at)"
                    >
                        <x-heroicon-s-eye class="w-5 h-5"/>
                    </x-secondary-button>
                </div>
                <div class="w-16">
                    @if ($unreadFeedItem->image_url)
                        <img src="{{ $unreadFeedItem->image_url }}" alt="{{ $unreadFeedItem->title }}" class="h-9 rounded"/>
                    @else
                        <div class="flex items-center justify-center bg-gray-300 rounded">
                            <svg xmlns="http://www.w3.org/2000/svg" class="text-white h-9 w-9" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    @endif
                </div>
                <div>
                    <div>{{ $unreadFeedItem->title }}</div>
                    <div class="flex space-x-2 text-gray-500 text-xs">
                        <div>{{ $unreadFeedItem->posted_at->format(__('date.datetime')) }}</div>
                        <div>{{ $unreadFeedItem->feed->name }}</div>
                    </div>
                </div>
            </a>
        @endforeach
    </x-card.card>
</x-app-layout>
