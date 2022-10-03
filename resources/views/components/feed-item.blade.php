@props(['feedItem'])

<x-card.card class="grow">
    <div
        class="flex flex-col justify-between h-full block transition ease-out duration-300 sm:rounded-md"
        :class="{ 'opacity-40': isRead(@json($feedItem->id)) }"
    >
        <div class="sm:rounded-t-md">
            @if ($feedItem->has_image)
                <x-lazy-image
                    :src="$feedItem->image_url"
                    :alt="$feedItem->title"
                    class="object-cover w-full h-72 md:h-56 sm:rounded-t-md transition"
                />
            @else
                <x-heroicon-s-photograph class="fill-current text-white dark:text-gray-400 bg-gray-300 dark:bg-gray-700 w-full h-72 md:h-56 sm:rounded-t-md transition"/>
            @endif
        </div>

        <x-card.body class="grow flex flex-col">
            <a
                href="{{ $feedItem->url }}"
                lang="{{ $feedItem->feed->language }}"
                class="text-primary-600 dark:text-primary-400 hover:text-primary-700 hover:text-primary-300 hover:underline text-2xl overflow-hidden hyphens-auto break-words transition"
            >
                {{ $feedItem->title }}
            </a>
            <div class="w-full text-muted pt-2">
                <div class="flex items-center">
                    @if ($feedItem->feed->favicon_url)
                        <x-lazy-image :src="$feedItem->feed->favicon_url" class="w-4 h-4"/>
                    @else
                        <x-heroicon-o-photograph class="h-4 w-4"/>
                    @endif

                    <div class="ml-1 text-sm">{{ $feedItem->feed->name }}</div>
                </div>
                <div class="pt-2 text-sm">{{ $feedItem->formatted_posted_at }}</div>
            </div>

            @if (Auth::user()->is_feed_item_description_visible && $feedItem->description)
                <div class="grow pt-2 text-muted overflow-hidden line-clamp-6 xl:line-clamp-3 break-all">{{ $feedItem->description }}</div>
            @endif

            <button
                type="button"
                class="mt-4 w-full inline-flex items-center p-4 bg-white dark:bg-transparent border border-gray-300 dark:border-gray-700 rounded-md font-semibold text-xs text-gray-600 uppercase tracking-widest shadow dark:shadow-black/25 hover:text-white hover:bg-primary-700 dark:hover:bg-primary-500 active:bg-gray-900 dark:active:bg-primary-800 focus:outline-none focus:border-gray-900 focus:ring-2 focus:ring-primary-300/75 dark:focus:ring-primary-400/75 ring-offset-2 focus:ring-primary-200 dark:focus:ring-primary-600 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition"
                :disabled="isLoading(@json($feedItem->id))"
                @click.prevent="toggleMarkAsRead(@json($feedItem->id))"
            >
                <template x-if="isLoading(@json($feedItem->id))">
                    <x-icon.loading class="mr-2"/>
                </template>
                <template x-if="!isLoading(@json($feedItem->id)) && !isRead(@json($feedItem->id))">
                    <x-heroicon-s-eye class="w-5 h-5 mr-2"/>
                </template>
                <template x-if="!isLoading(@json($feedItem->id)) && isRead(@json($feedItem->id))">
                    <x-heroicon-s-eye-off class="w-5 h-5 mr-2"/>
                </template>

                <span>{{ __('Mark as read') }}</span>
            </button>
        </x-card.body>
    </div>
</x-card.card>
