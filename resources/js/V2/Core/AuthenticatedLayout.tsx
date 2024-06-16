import {Outlet, useLoaderData, useLocation, useMatches} from 'react-router-dom';
import Breadcrumbs from '@/V2/Core/Breadcrumbs';
import {useEffect} from 'react';
import MatchWithHandle from '@/V2/types/MatchWithHandle';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Dropdown from '@/Components/Dropdown';
import DropdownArrowIcon from '@/Icons/DropdownArrowIcon';
import User from '@/types/generated/Models/User';

const AuthenticatedLayout = () => {
    const user = useLoaderData() as User;
    const {t} = useLaravelReactI18n();
    const location = useLocation();
    const match = useMatches().find((match) => match.pathname === location.pathname) as MatchWithHandle;

    useEffect(() => {
        document.querySelector('title')!.textContent = t(match?.handle.title);
    }, [location]);

    return (
        <div className="min-h-screen text-gray-800 dark:text-gray-300 bg-gray-100 dark:bg-gray-900">
            <header className="flex justify-between items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-xl space-x-2">
                <Breadcrumbs/>

                <Dropdown>
                    <Dropdown.Trigger>
                        <span className="inline-flex rounded-md">
                            <button
                                type="button"
                                className="inline-flex items-center space-x-2 text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 dark:hover:text-white px-3 py-2 rounded-md text-sm transition"
                            >
                                <span>{user.name}</span>

                                <DropdownArrowIcon/>
                            </button>
                        </span>
                    </Dropdown.Trigger>

                    <Dropdown.Content>
                        <Dropdown.Link to="/v2/categories">
                            {t('Categories')}
                        </Dropdown.Link>
                    </Dropdown.Content>
                </Dropdown>
            </header>

            <main className="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                <Outlet/>
            </main>
        </div>
    );
};

export default AuthenticatedLayout;
