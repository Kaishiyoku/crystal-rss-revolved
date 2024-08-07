/* this file has been automatically generated */
import Category from '@/types/generated/Models/Category';
import Feed from '@/types/generated/Models/Feed';
import FeedItem from '@/types/generated/Models/FeedItem';

type User = {
    id: number /** cast attribute */;
    name: string;
    email: string;
    email_verified_at: string | null /** cast attribute */;
    password: string;
    remember_token: string | null;
    is_admin: boolean /** cast attribute */;
    created_at: string | null;
    updated_at: string | null;
    categories: Category[];
    feeds: Feed[];
    feed_items: FeedItem[];
};

export default User;
