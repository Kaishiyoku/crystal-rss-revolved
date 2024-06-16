import FeedWithFeedItemsCount from '@/types/generated/Models/FeedWithFeedItemsCount';

type FeedsLoaderType = {
    feeds: FeedWithFeedItemsCount[];
    canCreate: boolean;
}
export default FeedsLoaderType;
