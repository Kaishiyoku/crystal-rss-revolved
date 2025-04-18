import { atom } from "jotai";
import ShortFeedWithFeedItemsCount from "@/types/models/ShortFeedWithFeedItemsCount";
import { FeedItem } from "@/types/generated/models";
import { pluck, prop, sortBy } from "ramda";

export const unreadFeedsAtom = atom<ShortFeedWithFeedItemsCount[]>([]);

export const totalNumberOfFeedItemsAtom = atom((get) => {
	const unreadFeedsAtomValue = get(unreadFeedsAtom);

	return unreadFeedsAtomValue.reduce(
		(accum, unreadFeed) => accum + unreadFeed.feed_items_count,
		0,
	);
});

export const updateUnreadFeedByFeedItem =
	(feedItem: FeedItem) => (prev: ShortFeedWithFeedItemsCount[]) => {
		return prev.map((unreadFeed) =>
			unreadFeed.id === feedItem.feed_id
				? {
						...unreadFeed,
						feed_items_count:
							unreadFeed.feed_items_count + (feedItem.read_at ? -1 : 1),
					}
				: unreadFeed,
		);
	};

export const mergeWithEmptyUnreadFeeds =
	(
		newUnreadFeeds: ShortFeedWithFeedItemsCount[],
		prevUnreadFeeds: ShortFeedWithFeedItemsCount[],
	) =>
	() => {
		const newUnreadFeedIds = pluck("id", newUnreadFeeds);

		return sortBy(prop("name"), [
			...newUnreadFeeds,
			...prevUnreadFeeds.filter(
				(prevUnreadFeed) =>
					prevUnreadFeed.feed_items_count === 0 &&
					!newUnreadFeedIds.includes(prevUnreadFeed.id),
			),
		]);
	};
