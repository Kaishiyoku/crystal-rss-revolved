import FeedWithFeedItemsCount from "@/types/models/FeedWithFeedItemsCount";

type ShortFeedWithFeedItemsCount = Pick<
	FeedWithFeedItemsCount,
	"id" | "name" | "favicon_url" | "feed_items_count"
>;

export default ShortFeedWithFeedItemsCount;
