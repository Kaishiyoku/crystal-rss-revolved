import { ReactNode } from "react";
import { BasePageProps, PageProps } from "@/types";
import Navigation from "@/Core/Navigation";
import { usePage } from "@inertiajs/react";
import { Heading } from "@/Components/Heading";
import Breadcrumb from "@/types/Breadcrumb";
import Breadcrumbs from "@/Core/Breadcrumbs";

type AuthenticatedLayoutProps =
	| (BasePageProps & {
			header: ReactNode;
			breadcrumbs?: Breadcrumb[] | undefined;
			actions?: ReactNode;
			children: ReactNode;
	  })
	| (BasePageProps & {
			header?: ReactNode;
			breadcrumbs: Breadcrumb[] | undefined;
			actions?: ReactNode;
			children: ReactNode;
	  });

export default function Authenticated({
	auth,
	header,
	breadcrumbs,
	actions,
	children,
}: AuthenticatedLayoutProps) {
	const { selectedFeedId } = usePage<PageProps>().props;

	return (
		<Navigation user={auth.user} selectedFeedId={selectedFeedId}>
			{(header || breadcrumbs) && (
				<div className="flex w-full flex-wrap items-end justify-between gap-4 border-b border-zinc-950/10 mb-8 pb-6 dark:border-white/10">
					{header && !breadcrumbs && <Heading>{header}</Heading>}

					{breadcrumbs && <Breadcrumbs breadcrumbs={breadcrumbs} />}

					{actions && <div className="flex gap-4">{actions}</div>}
				</div>
			)}

			<main>{children}</main>
		</Navigation>
	);
}
