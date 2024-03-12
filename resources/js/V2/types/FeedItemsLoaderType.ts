import FeedItem from '@/types/generated/Models/FeedItem';
import CursorPagination from '@/types/CursorPagination';

type FeedItemsLoaderType = {
    feedItems: CursorPagination<FeedItem>;
}
export default FeedItemsLoaderType;
