import Feed from '@/types/Models/Feed';

type ShortFeed = Pick<Feed, 'id' | 'name' | 'feed_items_count'>;

export default ShortFeed;
