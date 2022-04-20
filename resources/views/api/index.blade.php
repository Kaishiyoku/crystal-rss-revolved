<x-app-layout>
    <x-slot name="header">
        <x-site.heading>
            {{ __('API Tokens') }}
        </x-site.heading>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('api.api-token-manager')
        </div>
    </div>
</x-app-layout>
