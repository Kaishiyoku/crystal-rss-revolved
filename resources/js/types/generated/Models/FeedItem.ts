/* this file has been automatically generated */
import Feed from '@/types/generated/Models/Feed';

type FeedItem = {
    id: number /** cast attribute */;
    feed_id: number;
    checksum: string;
    url: string;
    title: string;
    image_url: string | null;
    image_mimetype: string | null;
    blur_hash: string | null;
    description: string | null;
    posted_at: string /** cast attribute */;
    read_at: string /** cast attribute */;
    created_at: string | null;
    updated_at: string | null;
    has_image: boolean /** model attribute */;
    feed: Feed;
}

export default FeedItem;
