import Category from '@/types/Models/Category';

type Feed = {
    id: number;
    favicon_url: string | null;
    name: string;
    feed_items_count: number;
    last_failed_at: string | null;
    category_id: number;
    feed_url: string;
    site_url: string;
    language: string;
    is_purgeable: boolean;
    created_at: string;
    last_checked_at: string | null;
    updated_at: string | null;
    user_id: number;
    category: Category;
};

export default Feed;
