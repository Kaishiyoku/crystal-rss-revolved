<div x-data="feedDiscoverer()">
    <div class="relative">
        <x-jet-input id="feed_url" class="block mt-1 w-full pr-10" type="text" name="feed_url" :value="old('feed_url', $feed->feed_url)" required x-ref="feedUrlInput" @keyup.debounce="handleInputChange"/>
        <x-icon.loading class="absolute top-2.5 right-2.5" x-show="isLoading"/>
    </div>

    @if ($discoveredFeedUrls->isNotEmpty())
        <div class="absolute rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white overflow-y-auto max-w-[300px] max-h-[250px]" x-show="!isFeedUrlSelected && !errorMessage">
            @foreach ($discoveredFeedUrls as $discoveredFeedUrl)
                <button
                    type="button"
                    class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 transition overflow-hidden overflow-ellipsis"
                    @click="selectFeedUrl('{{ $discoveredFeedUrl }}')"
                >
                    {{ $discoveredFeedUrl }}
                </button>
            @endforeach
        </div>
    @endif

    <x-validation-error>
        <div x-text="errorMessage"></div>
    </x-validation-error>
</div>

@push('scripts')
    <script type="text/javascript">
        function feedDiscoverer() {
            return {
                errorMessage: null,
                isFeedUrlSelected: false,
                isLoading: false,
                init() {
                    this.$wire.on('discoveryFailed', (errorMessage) => {
                        this.errorMessage = errorMessage;
                        this.isLoading = false;
                    });
                    this.$wire.on('discoverySuccess', () => {
                        this.errorMessage = null;
                        this.isFeedUrlSelected = false;
                        this.isLoading = false;
                    });
                    this.$wire.on('feedMetadata', (feedMetadata) => {
                        document.querySelector('{{ $siteUrlInputElementSelector }}').value = feedMetadata.siteUrl;
                        document.querySelector('{{ $nameInputElementSelector }}').value = feedMetadata.name;
                        this.isLoading = false;
                    });
                },
                handleInputChange(event) {
                    const value = event.target.value;

                    if (!value) {
                        return;
                    }

                    this.isLoading = true;

                    this.$wire.discover(event.target.value);
                },
                selectFeedUrl(feedUrl) {
                    this.isLoading = true;

                    this.$refs.feedUrlInput.value = feedUrl;
                    this.isFeedUrlSelected = true;

                    this.$wire.retrieveFeedMetadata(feedUrl);
                },
            };
        }
    </script>
@endpush
