import Breadcrumb from "@/types/Breadcrumb";
import { User } from "@/types/generated/models";
import ShortFeedWithFeedItemsCount from "@/types/models/ShortFeedWithFeedItemsCount";

export type BasePageProps = {
	errors: object;
	auth: {
		user: User;
	};
};

export type PageProps<
	T extends Record<string, unknown> = Record<string, unknown>,
> = T &
	BasePageProps & {
		breadcrumbs?: Breadcrumb[];
		monthsAfterPruningFeedItems: number;
		selectedFeedId: number | null;
		unreadFeeds: ShortFeedWithFeedItemsCount[];
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
