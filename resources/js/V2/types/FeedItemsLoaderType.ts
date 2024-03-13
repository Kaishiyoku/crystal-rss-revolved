import FeedItem from '@/types/generated/Models/FeedItem';
import CursorPagination from '@/types/CursorPagination';
import Feed from '@/types/generated/Models/Feed';

type FeedItemsLoaderType = {
    selectedFeed: Feed | null;
    totalNumberOfFeedItems: number;
    unreadFeeds: Feed[];
    feedItems: CursorPagination<FeedItem>;
    currentCursor: number;
}
export default FeedItemsLoaderType;
