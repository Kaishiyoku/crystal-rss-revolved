import {useMatches} from 'react-router-dom';
import Breadcrumb from '@/V2/types/Breadcrumb';
import RouteHandle from '@/V2/types/RouteHandle';

export default function useBreadcrumbs() {
    const matches = useMatches();

    return matches
        .filter((match) => !!match.handle && !(match.handle as RouteHandle).hide)
        .map((match) => {
            const handle = match.handle as RouteHandle;

            return {pathname: match.pathname, titleKey: handle.titleKey} as Breadcrumb;
        }) as Breadcrumb[];
}
