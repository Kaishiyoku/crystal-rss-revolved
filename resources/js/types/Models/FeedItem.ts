import Feed from '@/types/Models/Feed';

type FeedItem = {
    id: number;
    checksum: string;
    created_at: string;
    feed_id: number;
    image_mimetype: string | null;
    laravel_through_key: number;
    updated_at: string | null;
    has_image: boolean;
    image_url: string | null;
    title: string;
    url: string;
    posted_at: string;
    feed: Feed;
    description: string;
    read_at: string | null;
    blur_hash: string | null;
};

export default FeedItem;
