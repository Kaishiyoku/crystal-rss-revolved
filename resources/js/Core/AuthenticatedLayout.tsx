import {Outlet, useLoaderData, useLocation, useMatches} from 'react-router-dom';
import Breadcrumbs from '@/Core/Breadcrumbs';
import {Fragment, useEffect, useState} from 'react';
import MatchWithHandle from '@/types/MatchWithHandle';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Dropdown from '@/Components/Dropdown';
import DropdownArrowIcon from '@/Icons/DropdownArrowIcon';
import useAuth from '@/Hooks/useAuth';
import User from '@/types/generated/Models/User';
import request from '@/Core/request';
import {Transition} from '@headlessui/react';
import clsx from 'clsx';

const AuthenticatedLayout = () => {
    const {user, setUser} = useAuth();
    const userData = useLoaderData() as User;

    const {t} = useLaravelReactI18n();
    const location = useLocation();
    const match = useMatches().find((match) => match.pathname === location.pathname) as MatchWithHandle;

    const [isNavigationMenuOpen, setIsNavigationMenuOpen] = useState(false);

    useEffect(() => {
        setUser(userData);
    }, []);

    useEffect(() => {
        if (match?.handle) {
            document.querySelector('title')!.textContent = t(match?.handle.titleKey);
        }

        setIsNavigationMenuOpen(false);
    }, [location]);

    useEffect(() => {
        document.body.style.overflowY = isNavigationMenuOpen ? 'hidden' : '';
        document.body.style.position = isNavigationMenuOpen ? 'fixed' : '';
        document.body.style.width = isNavigationMenuOpen ? '100%' : '';
    }, [isNavigationMenuOpen]);

    const handleLogout = async () => {
        await request.delete('/api/logout');

        window.location.href = '/';
    };

    if (!user) {
        return null;
    }

    return (
        <div className="min-h-screen text-gray-800 dark:text-gray-300 bg-gray-50 dark:bg-gray-900">
            <header
                className="flex justify-between items-center max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-xl space-x-2">
                <Breadcrumbs/>

                <Dropdown>
                    <Dropdown.Trigger className="hidden sm:block">
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
                        <Dropdown.Link to="/profile">
                            {t('Profile')}
                        </Dropdown.Link>

                        <Dropdown.Link to="/categories">
                            {t('Categories')}
                        </Dropdown.Link>

                        <Dropdown.Link to="/feeds">
                            {t('Feeds')}
                        </Dropdown.Link>

                        {user.is_admin && (
                            <>
                                <Dropdown.Spacer/>

                                <Dropdown.Link to="/admin/users">
                                    {t('Users')}
                                </Dropdown.Link>

                                <Dropdown.Link to="/telescope" external>
                                    {t('Telescope')}
                                </Dropdown.Link>

                                <Dropdown.Link to="/horizon" external>
                                    {t('Horizon')}
                                </Dropdown.Link>
                            </>
                        )}

                        <Dropdown.Spacer/>

                        <Dropdown.Button onClick={handleLogout}>
                            {t('Logout')}
                        </Dropdown.Button>
                    </Dropdown.Content>
                </Dropdown>

                <button
                    onClick={() => setIsNavigationMenuOpen((prevState) => !prevState)}
                    className={clsx(
                        'sm:hidden inline-flex items-center justify-center rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out',
                        {
                            'fixed z-30 right-4': isNavigationMenuOpen,
                        }
                    )}
                >
                    <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path
                            className={!isNavigationMenuOpen ? 'inline-flex' : 'hidden'}
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M4 6h16M4 12h16M4 18h16"
                        />
                        <path
                            className={isNavigationMenuOpen ? 'inline-flex' : 'hidden'}
                            strokeLinecap="round"
                            strokeLinejoin="round"
                            strokeWidth="2"
                            d="M6 18L18 6M6 6l12 12"
                        />
                    </svg>
                </button>
            </header>

            <main className="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                <Outlet/>
            </main>

            <Transition show={isNavigationMenuOpen} as={Fragment} leave="duration-200">
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0 -translate-y-8 sm:translate-y-0"
                    enterTo="opacity-100 translate-y-0"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100 translate-y-0"
                    leaveTo="opacity-0 -translate-y-8 sm:translate-y-0"
                >
                    <div className="fixed overflow-y-auto scrollbar-y-sm px-4 pt-16 pb-16 top-0 bg-white/50 dark:bg-gray-800/50 z-20 w-full h-full backdrop-blur-lg sm:hidden">
                        <div className="pt-2 pb-3 space-y-1">
                            <Dropdown.Link to="/app">
                                {t('Home')}
                            </Dropdown.Link>

                            <Dropdown.Spacer/>

                            <Dropdown.Link to="/profile">
                                {t('Profile')}
                            </Dropdown.Link>

                            <Dropdown.Link to="/categories">
                                {t('Categories')}
                            </Dropdown.Link>

                            <Dropdown.Link to="/feeds">
                                {t('Feeds')}
                            </Dropdown.Link>

                            {user.is_admin && (
                                <>
                                    <Dropdown.Spacer/>

                                    <Dropdown.Link to="/admin/users">
                                        {t('Users')}
                                    </Dropdown.Link>

                                    <Dropdown.Link to="/telescope" external>
                                        {t('Telescope')}
                                    </Dropdown.Link>

                                    <Dropdown.Link to="/horizon" external>
                                        {t('Horizon')}
                                    </Dropdown.Link>
                                </>
                            )}
                        </div>

                        <div className="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                            <div className="px-4">
                                <div className="font-medium text-base text-gray-800 dark:text-gray-200">
                                    {user.name}
                                </div>
                                <div className="font-medium text-sm text-gray-500">{user.email}</div>
                            </div>

                            <div className="mt-3 space-y-1">
                                <Dropdown.Button onClick={handleLogout}>
                                    {t('Logout')}
                                </Dropdown.Button>
                            </div>
                        </div>
                    </div>
                </Transition.Child>
            </Transition>
        </div>
    );
};

export default AuthenticatedLayout;
