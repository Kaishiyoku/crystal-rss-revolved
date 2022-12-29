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
                class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 hover:underline text-2xl overflow-hidden hyphens-auto break-words transition"
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
                class="inline-flex items-center transition ease-in disabled:opacity-50 disabled:cursor-not-allowed font-semibold text-xs uppercase tracking-widest border focus:outline-none focus:ring-1 shadow focus:shadow-md dark:shadow-black rounded-md px-4 py-3 sm:py-2 text-gray-900 border-gray-300 bg-white hover:border-gray-300 hover:bg-gray-100 focus:ring-gray-300 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:border-gray-500 dark:hover:bg-gray-600 dark:focus:ring-gray-600"
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
