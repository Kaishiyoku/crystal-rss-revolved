<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Feeds') }}
            </h2>

            <x-secondary-button-link :url="route('feeds.create')">
                {{ __('Add') }}
            </x-secondary-button-link>
        </div>
    </x-slot>

    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg divide-y divide-gray-100">
        @foreach ($feeds as $feed)
            <a class="flex items-center space-x-4 px-4 py-3 transition first:rounded-t-md last:rounded-b-md hover:bg-gray-50" href="{{ route('feeds.edit', $feed) }}">
                {{ $feed->name }}
            </a>
        @endforeach
    </div>
</x-app-layout>
