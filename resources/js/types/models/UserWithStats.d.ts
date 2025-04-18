import { User } from "@/types/generated/models";

type UserWithStats = User & {
	feeds_count: number;
	unread_feed_items_count: number;
};

export default UserWithStats;
