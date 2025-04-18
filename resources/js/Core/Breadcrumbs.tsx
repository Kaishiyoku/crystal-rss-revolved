import slug from "slug";
import { useEffect, useRef } from "react";
import type Breadcrumb from "@/types/Breadcrumb";
import { Link } from "@/Components/Link";

export default function Breadcrumbs({
	breadcrumbs,
}: { breadcrumbs?: Breadcrumb[] }) {
	if (!breadcrumbs) {
		return null;
	}

	const breadcrumbsRef = useRef<HTMLDivElement>(null);

	useEffect(() => {
		setTimeout(() => {
			breadcrumbsRef.current?.scrollTo({
				top: 0,
				left: breadcrumbsRef.current.getBoundingClientRect().right,
				behavior: "smooth",
			});
		}, 250);
	}, []);

	const breadcrumbMapper = (
		breadcrumb: Breadcrumb,
		index: number,
		arr: Breadcrumb[],
	) => {
		const breadcrumbElement = breadcrumb.url ? (
			<li key={slug(breadcrumb.title)}>
				<Link
					href={breadcrumb.url}
					className="font-semibold"
					color="blue"
					hover
				>
					{breadcrumb.title}
				</Link>
			</li>
		) : (
			<li
				key={slug(breadcrumb.title)}
				className="font-semibold text-zinc-800 dark:text-zinc-300 leading-tight"
			>
				{breadcrumb.title}
			</li>
		);

		if (index === arr.length - 1) {
			return breadcrumbElement;
		}

		return [
			breadcrumbElement,
			<li
				key={`${slug(breadcrumb.title)}-separator`}
				className="text-zinc-300 dark:text-zinc-500"
			>
				<svg
					className="h-5 w-5 shrink-0 stroke-current"
					xmlns="http://www.w3.org/2000/svg"
					fill="currentColor"
					viewBox="0 0 20 20"
					aria-hidden="true"
				>
					<path
						d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z"
						strokeWidth=".5"
					/>
				</svg>
			</li>,
		];
	};

	return (
		<div
			className="overflow-y-hidden overflow-x-auto scrollbar-x-sm whitespace-nowrap text-2xl/8 sm:text-xl/8"
			ref={breadcrumbsRef}
		>
			<nav className="flex" aria-label="Breadcrumb">
				<ol className="flex items-center space-x-2">
					{breadcrumbs.flatMap(breadcrumbMapper)}
				</ol>
			</nav>
		</div>
	);
}
