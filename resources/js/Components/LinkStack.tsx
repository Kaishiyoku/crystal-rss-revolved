import type { ComponentPropsWithoutRef, ReactNode } from "react";
import { Link } from "@inertiajs/react";
import { clsx } from "clsx";

export function LinkStack({
	children,
	...props
}: { children: ReactNode } & ComponentPropsWithoutRef<"div">) {
	return <div {...props}>{children}</div>;
}

type LinkStackItemProps =
	| {
			image?: ReactNode;
			url: string;
			title: string;
			onClick?: () => void;
			children?: ReactNode;
			disabled?: boolean;
	  }
	| {
			image?: ReactNode;
			url?: string;
			title: string;
			onClick: () => void;
			children?: ReactNode;
			disabled?: boolean;
	  };

export function LinkStackItem({
	image,
	url,
	title,
	onClick,
	children,
	disabled = false,
}: LinkStackItemProps) {
	const classes = clsx(
		"block w-full text-left px-2 py-2.5 first:rounded-t-md last:rounded-b-md transition duration-75 ease-out",
		{
			"opacity-50": disabled,
			"hover:bg-zinc-50 dark:hover:bg-zinc-800/50": !disabled,
		},
	);

	return url ? (
		<Link href={url} className={classes} disabled={disabled}>
			{image ? (
				<div className="flex space-x-2">
					{image}
					<div className="font-semibold">{title}</div>
				</div>
			) : (
				<div className="font-semibold">{title}</div>
			)}

			{children && <div className="text-muted">{children}</div>}
		</Link>
	) : (
		<button
			type="button"
			onClick={onClick}
			className={classes}
			disabled={disabled}
		>
			{image ? (
				<div className="flex space-x-2">
					{image}
					<div className="font-semibold">{title}</div>
				</div>
			) : (
				<div className="font-semibold">{title}</div>
			)}

			{children && <div className="text-muted">{children}</div>}
		</button>
	);
}
