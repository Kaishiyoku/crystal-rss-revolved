import {Link} from 'react-router-dom';
import slug from 'slug';
import {Fragment, useEffect, useRef} from 'react';
import useBreadcrumbs from '@/V2/Hooks/useBreadcrumbs';
import Breadcrumb from '@/V2/types/Breadcrumb';
import {useLaravelReactI18n} from 'laravel-react-i18n';

const LinkBreadcrumb = ({breadcrumb}: { breadcrumb: Breadcrumb; }) => {
    const {t} = useLaravelReactI18n();

    return (
        <li>
            {t(breadcrumb.headline)}
        </li>
    );
};

const TextBreadcrumb = ({breadcrumb}: { breadcrumb: Breadcrumb; }) => {
    const {t} = useLaravelReactI18n();

    return (
        <Fragment>
            <li>
                <Link to={breadcrumb.pathname} className="font-semibold text-violet-400 hover:text-violet-300 leading-tight transition">
                    {t(breadcrumb.headline)}
                </Link>
            </li>

            <li>
                <svg
                    className="h-5 w-5 flex-shrink-0 stroke-current"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                    aria-hidden="true"
                >
                    <path d="M5.555 17.776l8-16 .894.448-8 16-.894-.448z" strokeWidth=".5"/>
                </svg>
            </li>
        </Fragment>
    );
};

export default function Breadcrumbs() {
    const breadcrumbs = useBreadcrumbs();
    const breadcrumbsRef = useRef<HTMLDivElement>(null);

    useEffect(() => {
        setTimeout(() => {
            breadcrumbsRef.current?.scrollTo({
                top: 0,
                left: breadcrumbsRef.current.getBoundingClientRect().right,
                behavior: 'smooth',
            });
        }, 250);
    }, []);

    return (
        <div className="overflow-y-hidden overflow-x-auto scrollbar-x-sm whitespace-nowrap" ref={breadcrumbsRef}>
            <nav aria-label="Breadcrumbs" ref={breadcrumbsRef}>
                <ol role="list" className="flex items-center space-x-2">
                    {breadcrumbs.map((breadcrumb, index) =>
                        index === breadcrumbs.length - 1
                            ? <LinkBreadcrumb key={slug(breadcrumb.headline)} breadcrumb={breadcrumb}/>
                            : <TextBreadcrumb key={slug(breadcrumb.headline)} breadcrumb={breadcrumb}/>
                    )}
                </ol>
            </nav>
        </div>
    );
}
