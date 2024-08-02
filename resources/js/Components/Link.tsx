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

export const Link = React.forwardRef(function Link({external = false, ...props}: { external?: boolean; } & InertiaLinkProps & React.ComponentPropsWithoutRef<'a'>, ref: React.ForwardedRef<HTMLAnchorElement>) {
    return (
        <Headless.DataInteractive>
            {external
                ? <a {...props} ref={ref}/>
                : <InertiaLink {...props} ref={ref}/>}
        </Headless.DataInteractive>
    );
});
