export interface Category {
	// columns
	id: number;
	user_id: number;
	name: string;
	// relations
	user: User;
	feeds: Feed[];
	// counts
	feeds_count: number;
	// exists
	user_exists: boolean;
	feeds_exists: boolean;
}

export interface Feed {
	// columns
	id: number;
	user_id: number;
	category_id: number;
	feed_url: string;
	site_url: string;
	favicon_url: string | null;
	name: string;
	language: string;
	last_checked_at: string | null;
	created_at: string | null;
	updated_at: string | null;
	last_failed_at: string | null;
	is_purgeable: boolean;
	// relations
	user: User;
	category: Category;
	feed_items: FeedItem[];
	// counts
	feed_items_count: number;
	// exists
	user_exists: boolean;
	category_exists: boolean;
	feed_items_exists: boolean;
}

export interface FeedItem {
	// columns
	id: number;
	feed_id: number;
	checksum: string;
	url: string;
	title: string;
	image_url: string | null;
	image_mimetype: string | null;
	description: string | null;
	posted_at: string;
	read_at: string | null;
	created_at: string | null;
	updated_at: string | null;
	blur_hash: string | null;
	// mutators
	has_image: boolean;
	// relations
	feed: Feed;
	// counts
	// exists
	feed_exists: boolean;
}

export interface User {
	// columns
	id: number;
	name: string;
	email: string;
	email_verified_at: string | null;
	password?: string;
	remember_token?: string | null;
	created_at: string | null;
	updated_at: string | null;
	is_admin: boolean;
	// overrides
	tokens: unknown;
	notifications: unknown;
	// relations
	categories: Category[];
	feeds: Feed[];
	feed_items: FeedItem[];
	// counts
	categories_count: number;
	feeds_count: number;
	feed_items_count: number;
	// exists
	categories_exists: boolean;
	feeds_exists: boolean;
	feed_items_exists: boolean;
}
