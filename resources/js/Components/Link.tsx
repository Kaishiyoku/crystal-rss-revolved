/**
 * TODO: Update this component to use your client-side framework's link
 * component. We've provided examples of how to do this for Next.js, Remix, and
 * Inertia.js in the Catalyst documentation:
 *
 * https://catalyst.tailwindui.com/docs#client-side-router-integration
 */

import * as Headless from '@headlessui/react';
import React from 'react';
import {InertiaLinkProps, Link as InertiaLink} from '@inertiajs/react';
import {clsx} from 'clsx';
import {twMerge} from 'tailwind-merge';

export const Link = React.forwardRef(function Link({external = false, hover = false, color = 'zinc', className, ...props}: { external?: boolean; hover?: boolean; color?: 'zinc' | 'blue'; } & InertiaLinkProps & React.ComponentPropsWithoutRef<'a'>, ref: React.ForwardedRef<HTMLAnchorElement>) {
    const colorClasses = clsx({
        'text-blue-600 dark:text-blue-400': color === 'blue',
        'hover:underline': hover,
    });

    return (
        <Headless.DataInteractive>
            {external
                ? <a {...props} ref={ref} className={twMerge(colorClasses, className)}/>
                : <InertiaLink {...props} ref={ref} className={twMerge(colorClasses, className)}/>}
        </Headless.DataInteractive>
    );
});
