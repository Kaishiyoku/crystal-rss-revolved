import {Link} from '@inertiajs/react';

export default function ResponsiveNavLink({component = Link, active = false, className = '', children, ...props}) {
    const Component = component;

    return (
        <Component
            {...props}
            className={`w-full flex items-start pl-3 pr-4 py-2 border-l-4 ${
                active
                    ? 'border-violet-400 dark:border-violet-600 text-violet-700 dark:text-violet-300 bg-violet-50 dark:bg-violet-900/50 focus:text-violet-800 dark:focus:text-violet-200 focus:bg-violet-100 dark:focus:bg-violet-900 focus:border-violet-700 dark:focus:border-violet-300'
                    : 'border-transparent text-gray-600 dark:text-gray-400 hover:text-gray-800 dark:hover:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-700 hover:border-gray-300 dark:hover:border-gray-600 focus:text-gray-800 dark:focus:text-gray-200 focus:bg-gray-50 dark:focus:bg-gray-700 focus:border-gray-300 dark:focus:border-gray-600'
            } text-base font-medium focus:outline-none transition duration-150 ease-in-out ${className}`}
        >
            {children}
        </Component>
    );
}
