import {Link} from '@inertiajs/react';
import clsx from 'clsx';

export default function CardLink({className, children, ...props}) {
    return (
        <Link
            className={clsx('block px-4 py-2 transition dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 sm:first:rounded-t-md sm:last:rounded-b-md', className)}
            {...props}
        >
            {children}
        </Link>
    );
}
