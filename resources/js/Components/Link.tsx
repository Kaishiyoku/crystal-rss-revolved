import * as Headless from '@headlessui/react';
import { type InertiaLinkProps, Link as InertiaLink } from '@inertiajs/react';
import type React from 'react';
import { forwardRef } from 'react';
import { twMerge } from 'tailwind-merge';
import { clsx } from 'clsx';

export const Link = forwardRef(function Link(
	{
		external = false,
		hover = false,
		color = 'zinc',
		className,
		...props
	}: {
		external?: boolean;
		hover?: boolean;
		color?: 'zinc' | 'blue';
	} & InertiaLinkProps &
		React.ComponentPropsWithoutRef<'a'>,
	ref: React.ForwardedRef<HTMLAnchorElement>,
) {
	const colorClasses = clsx({
		'text-zinc-600 dark:text-zinc-400': color === 'zinc',
		'text-blue-600 dark:text-blue-400': color === 'blue',
		'underline decoration-transparent': hover,
		'hover:decoration-zinc-600 dark:hover:decoration-zinc-400':
			hover && color === 'zinc',
		'hover:decoration-blue-600 dark:hover:decoration-blue-400':
			hover && color === 'blue',
	});

	return (
		<Headless.DataInteractive>
			{external ? (
				<a {...props} ref={ref} className={twMerge(colorClasses, className)} />
			) : (
				<InertiaLink
					{...props}
					ref={ref}
					className={twMerge(colorClasses, className)}
				/>
			)}
		</Headless.DataInteractive>
	);
});
