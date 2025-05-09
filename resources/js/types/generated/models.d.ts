export interface Category {
	// columns
	id: number;
	user_id: number;
	name: string;
	// relations
	user: User;
	feeds: Feed[];
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
	is_purgeable: boolean;
	last_checked_at: string | null;
	last_failed_at: string | null;
	created_at: string | null;
	updated_at: string | null;
	// relations
	user: User;
	category: Category;
	feed_items: FeedItem[];
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
	blur_hash: string | null;
	description: string | null;
	posted_at: string;
	read_at: string | null;
	created_at: string | null;
	updated_at: string | null;
	// mutators
	has_image: boolean;
	// relations
	feed: Feed;
}

export interface User {
	// columns
	id: number;
	name: string;
	email: string;
	email_verified_at: string | null;
	password?: string;
	remember_token?: string | null;
	is_admin: boolean;
	created_at: string | null;
	updated_at: string | null;
	// overrides
	tokens: unknown;
	notifications: unknown;
	// relations
	categories: Category[];
	feeds: Feed[];
	feed_items: FeedItem[];
}
