import {Link} from '@inertiajs/react';
import clsx from 'clsx';

export default function NavLink({active = false, className = '', children, ...props}) {
    return (
        <Link
            {...props}
            className={clsx(
                'px-4 py-2 rounded-lg text-sm transition',
                {
                    'bg-violet-500/30 dark:bg-violet-500/40 text-violet-500 dark:text-violet-300': active,
                    'text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 dark:hover:text-white': !active,
                }
            )}
        >
            {children}
        </Link>
    );
}
