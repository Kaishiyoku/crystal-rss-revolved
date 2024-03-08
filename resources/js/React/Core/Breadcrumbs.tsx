import {Link} from 'react-router-dom';
import slug from 'slug';
import {Fragment} from 'react';
import useBreadcrumbs from '@/React/Hooks/useBreadcrumbs';
import Breadcrumb from '@/React/types/Breadcrumb';

const LinkBreadcrumb = ({breadcrumb}: { breadcrumb: Breadcrumb; }) => (
    <div>
        {breadcrumb.headline}
    </div>
);

const TextBreadcrumb = ({breadcrumb}: { breadcrumb: Breadcrumb; }) => (
    <Fragment>
        <Link to={breadcrumb.pathname}>
            {breadcrumb.headline}
        </Link>

        <div>/</div>
    </Fragment>
);

export default function Breadcrumbs() {
    const breadcrumbs = useBreadcrumbs();

    return breadcrumbs.map((breadcrumb, index) =>
        index === breadcrumbs.length - 1
            ? <LinkBreadcrumb key={slug(breadcrumb.headline)} breadcrumb={breadcrumb}/>
            : <TextBreadcrumb key={slug(breadcrumb.headline)} breadcrumb={breadcrumb}/>
    );
}
