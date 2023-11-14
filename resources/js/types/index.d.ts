export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at: string;
    is_admin: boolean;
}

export interface Feed {
    id: number;
    favicon_url: string | null;
    name: string;
    feed_items_count: number;
}

export interface FeedItem {
    id: number;
    has_image: boolean;
    image_url: string | null;
    title: string;
    url: string;
    posted_at: string;
    feed: Feed;
    description: string;
    read_at: string | null;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};

export type OtherProps = {
    [x: string]: unknown;
}

export type IconProps = {
    className?: string;
};
