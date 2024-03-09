import {Outlet, useLocation, useMatches} from 'react-router-dom';
import Breadcrumbs from '@/React/Core/Breadcrumbs';
import {useEffect} from 'react';
import MatchWithHandle from '@/React/types/MatchWithHandle';
import {useLaravelReactI18n} from 'laravel-react-i18n';

const AuthenticatedLayout = () => {
    const {t} = useLaravelReactI18n();
    const location = useLocation();
    const match = useMatches().find((match) => match.pathname === location.pathname) as MatchWithHandle;

    useEffect(() => {
        document.querySelector('title')!.textContent = t(match?.handle.title);
    }, [location]);

    return (
        <>
            <header className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-xl flex space-x-2">
                <Breadcrumbs/>
            </header>

            <main className="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                <Outlet/>
            </main>
        </>
    );
};

export default AuthenticatedLayout;
