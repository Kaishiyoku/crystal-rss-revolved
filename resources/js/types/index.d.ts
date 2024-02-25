import Breadcrumb from '@/types/Breadcrumb';
import User from '@/types/generated/Models/User';

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
};

export type IconProps = {
    className?: string;
};
