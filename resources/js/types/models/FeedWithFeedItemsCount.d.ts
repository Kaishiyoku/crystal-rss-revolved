import { Feed } from "@/types/generated/models";

type FeedWithFeedItemsCount = Feed & {
	feed_items_count: number;
};

export default FeedWithFeedItemsCount;
