@props(['feedItem'])

<x-card.card class="grow overflow-hidden">
    <a
        class="flex flex-col justify-between h-full group sm:hover:bg-primary-500 block transition ease-out duration-300 sm:focus:outline-none sm:focus:text-white sm:focus:bg-primary-600"
        href="{{ $feedItem->url }}"
        :class="{'opacity-40': isRead(@json($feedItem->id))}"
    >
        <div>
            @if ($feedItem->has_image)
                <img
                    src="{{ $feedItem->image_url }}"
                    alt="{{ $feedItem->title }}"
                    class="group-hover:brightness-125 object-cover w-full h-72 md:h-56 transition"
                    loading="lazy"
                />
            @else
                <x-heroicon-s-photograph class="group-hover:brightness-125 fill-current text-white dark:text-gray-400 bg-gray-300 dark:bg-gray-700 w-full h-72 md:h-56 transition"/>
            @endif
        </div>
        <div class="w-full px-4 py-3">
            <div class="sm:group-hover:text-white text-2xl overflow-hidden hyphens-auto break-words" lang="{{ $feedItem->feed->language }}">{{ $feedItem->title }}</div>
            <div class="group-hover:text-primary-600 dark:group-hover:text-primary-400 sm:group-hover:text-primary-200 w-full group-focus:text-primary-100 text-muted pt-2">
                <div class="flex items-center">
                    @if ($feedItem->feed->favicon_url)
                        <img src="{{ $feedItem->feed->favicon_url }}" class="w-4 h-4" alt="Favicon"/>
                    @else
                        <x-heroicon-o-photograph class="h-4 w-4"/>
                    @endif

                    <div class="ml-1">{{ $feedItem->feed->name }}</div>
                </div>
                <div>{{ $feedItem->formatted_posted_at }}</div>
            </div>

            @if (Auth::user()->is_feed_item_description_visible && $feedItem->description)
                <div class="group-hover:text-primary-600 dark:group-hover:text-primary-400 sm:group-hover:text-primary-200 group-focus:text-primary-100 pt-1 text-muted overflow-hidden line-clamp-6 xl:line-clamp-3 break-all">{{ $feedItem->description }}</div>
            @endif
        </div>

        <div class="mt-4 px-4 pb-4">
            <button
                type="button"
                class="hover:text-white sm:group-hover:text-white group-hover:border-primary-500 sm:group-hover:border-primary-300 w-full inline-flex items-center px-4 py-4 border border-gray-200 dark:border-gray-700 rounded-md hover:bg-primary-700 focus:bg-primary-800 focus:text-white hover:shadow-md focus:shadow-lg outline-none focus:ring focus:ring-primary-400 transition"
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
        </div>
    </a>
</x-card.card>
