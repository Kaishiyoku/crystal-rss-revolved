import {atom} from 'jotai';
import ShortFeedWithFeedItemsCount from '@/types/models/ShortFeedWithFeedItemsCount';
import {FeedItem} from '@/types/generated/models';

export const unreadFeedsAtom = atom<ShortFeedWithFeedItemsCount[]>([]);

export const totalNumberOfFeedItemsAtom = atom((get) => {
    const unreadFeedsAtomValue = get(unreadFeedsAtom);

    return unreadFeedsAtomValue.reduce((accum, unreadFeed) => accum + unreadFeed.feed_items_count, 0);
});

export const updateUnreadFeedByFeedItem = (feedItem: FeedItem) =>
    (prev: ShortFeedWithFeedItemsCount[]) => {
        return prev.map((unreadFeed) => unreadFeed.id === feedItem.feed_id
            ? {...unreadFeed, feed_items_count: unreadFeed.feed_items_count + (feedItem.read_at ? -1 : 1)}
            : unreadFeed
        );
    };
