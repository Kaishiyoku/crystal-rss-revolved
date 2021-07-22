<div>
    @foreach ($unreadFeedItems as $i => $unreadFeedItem)
        @include('feed.feed-item')
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
