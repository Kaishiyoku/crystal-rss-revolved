import clsx from 'clsx';
import type { ReactNode } from 'react';

type HeadingProps = {
	level?: 1 | 2 | 3 | 4 | 5 | 6;
} & React.ComponentPropsWithoutRef<'h1' | 'h2' | 'h3' | 'h4' | 'h5' | 'h6'>;

export function Heading({ className, level = 1, ...props }: HeadingProps) {
	const Element: `h${typeof level}` = `h${level}`;

	const classes = {
		'text-2xl/8 sm:text-xl/8': level === 1,
		'text-lg/8': level === 2,
		'text-md/7': level >= 3,
	};

	return (
		<Element
			{...props}
			className={clsx(
				className,
				classes,
				'font-semibold text-zinc-950 dark:text-white',
			)}
		/>
	);
}

export function Subheading({ className, level = 2, ...props }: HeadingProps) {
	const Element: `h${typeof level}` = `h${level}`;

	return (
		<Element
			{...props}
			className={clsx(
				className,
				'text-base/7 font-semibold text-zinc-950 sm:text-sm/6 dark:text-white',
			)}
		/>
	);
}

export function Description({ children }: { children: ReactNode }) {
	return <p className="mt-1 text-zinc-600 dark:text-zinc-400">{children}</p>;
}
