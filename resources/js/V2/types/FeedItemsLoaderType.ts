import FeedItem from '@/types/generated/Models/FeedItem';
import CursorPagination from '@/types/CursorPagination';
import ShortFeedWithFeedItemsCount from '@/types/generated/Models/ShortFeedWithFeedItemsCount';

type FeedItemsLoaderType = {
    selectedFeed: ShortFeedWithFeedItemsCount | null;
    totalNumberOfFeedItems: number;
    unreadFeeds: ShortFeedWithFeedItemsCount[];
    feedItems: CursorPagination<FeedItem>;
    currentCursor: number;
};

export default FeedItemsLoaderType;
