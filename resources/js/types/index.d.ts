type Breadcrumb = {
    title: string;
    url: string | null;
}

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
    last_failed_at: string | null;
    category_id: number;
    feed_url: string;
    site_url: string;
    language: string;
    is_purgeable: boolean;
}

export interface Category {
    id: number;
    name: string;
    feeds_count: number;
}

export type SelectNumberOption = {
    value: number;
    name: string;
};

export type SelectStringOption = {
    value: string;
    name: string;
};

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

export type CursorPagination<T> = {
    data: T[];
    next_cursor: string | null;
    next_page_url: string | null;
};

export type BasePageProps = {
    errors: object;
    auth: {
        user: User;
    };
};

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & BasePageProps & {
    breadcrumbs?: Breadcrumb[];
    monthsAfterPruningFeedItems: number;
};

export type WelcomeProps = PageProps & {
    contactEmail: string | null;
    githubUrl: string | null;
};

export type OtherProps = {
    [x: string]: unknown;
}

export type IconProps = {
    className?: string;
};
