import {useMatches} from 'react-router-dom';
import Breadcrumb from '@/React/types/Breadcrumb';

export default function useBreadcrumbs() {
    const matches = useMatches();

    return matches
        .filter((match) => !!match.handle)
        .map((match) => ({pathname: match.pathname, ...match.handle!})) as Breadcrumb[];
}
