import {Link} from '@inertiajs/react';
import slug from 'slug';
import {useEffect, useRef} from 'react';

/**
 * @param {Breadcrumb[]} breadcrumbs
 * @returns {JSX.Element|null}
 */
export default function Breadcrumbs({breadcrumbs}) {
    if (!breadcrumbs) {
        return null;
    }

    const breadcrumbsRef = useRef();

    useEffect(() => {
        setTimeout(() => {
            breadcrumbsRef.current.scrollTo({
                top: 0,
                left: breadcrumbsRef.current.getBoundingClientRect().right,
                behavior: 'smooth',
            })
        }, 250);
    }, []);

    /**
     * @param {Breadcrumb} breadcrumb
     * @param {number} index
     * @param {Breadcrumb[]} arr
     * @returns {JSX.Element}
     */
    const breadcrumbMapper = (breadcrumb, index, arr) => {
        const breadcrumbElement = breadcrumb.url ? (
            <li key={slug(breadcrumb.title)}>
                <Link href={breadcrumb.url} className="font-semibold text-indigo-400 hover:text-indigo-300 leading-tight transition">
                    {breadcrumb.title}
                </Link>
            </li>
        ) : (
            <li key={slug(breadcrumb.title)} className="font-semibold text-gray-800 dark:text-gray-300 leading-tight">
                {breadcrumb.title}
            </li>
        );

        if (index === arr.length - 1) {
            return breadcrumbElement;
        }

        return [
            breadcrumbElement,
            <li key={`${slug(breadcrumb.title)}-separator`} className="text-gray-300 dark:text-gray-500">
                <svg className="h-5 w-5 flex-shrink-0 stroke-current" xmlns="http://www.w3.org/2000/svg"
                     fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                    <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" strokeWidth=".5"/>
                </svg>
            </li>
        ];
    };

    return (
        <div className="overflow-y-hidden overflow-x-auto scrollbar-x-sm whitespace-nowrap" ref={breadcrumbsRef}>
            <nav className="flex" aria-label="Breadcrumb">
                <ol role="list" className="flex items-center space-x-2">
                    {breadcrumbs.map(breadcrumbMapper).flat()}
                </ol>
            </nav>
        </div>
    );
}
