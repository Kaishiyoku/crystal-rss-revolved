/* this file has been automatically generated */
import User from '@/types/generated/Models/User';

type UserWithStats = User & {
    feeds_count: number;
    unread_feed_items_count: number;
};

export default UserWithStats;
