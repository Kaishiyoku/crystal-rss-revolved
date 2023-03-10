import { Link } from '@inertiajs/react';

export default function NavLink({ active = false, className = '', children, ...props }) {
    return (
        <Link
            {...props}
            className={
                (active
                    ? 'bg-indigo-500/30 dark:bg-indigo-500/40 text-indigo-500 dark:text-indigo-300 px-3 py-2 rounded-md text-sm transition'
                    : 'text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 dark:hover:text-white px-3 py-2 rounded-md text-sm transition') +
                className
            }
        >
            {children}
        </Link>
    );
}
