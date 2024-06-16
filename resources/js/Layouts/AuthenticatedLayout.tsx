import {Fragment, ReactNode, useEffect, useState} from 'react';
import {Link} from '@inertiajs/react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import ApplicationLogo from '@/Components/ApplicationLogo';
import Dropdown from '@/Components/Dropdown';
import {Transition} from '@headlessui/react';
import DropdownArrowIcon from '@/Icons/DropdownArrowIcon';
import {BasePageProps} from '@/types';
import NavLink from '@/Components/NavLink';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink';

export default function Authenticated({auth, header, children}: BasePageProps & { header: ReactNode; children: ReactNode; }) {
    const {t} = useLaravelReactI18n();
    const [showingNavigationDropdown, setShowingNavigationDropdown] = useState(false);

    useEffect(() => {
        document.body.style.overflowY = showingNavigationDropdown ? 'hidden' : '';
        document.body.style.position = showingNavigationDropdown ? 'fixed' : '';
        document.body.style.width = showingNavigationDropdown ? '100%' : '';
    }, [showingNavigationDropdown]);

    return (
        <div className="min-h-screen text-gray-800 dark:text-gray-300 bg-gray-100 dark:bg-gray-900">
            <div className="shadow">
                <nav className="bg-white dark:bg-gray-800">
                    <div className="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div className="flex justify-between h-14">
                            <div className="flex">
                                <div className="shrink-0 flex items-center">
                                    <Link href="/">
                                        <ApplicationLogo
                                            className="block h-9 w-auto fill-current text-gray-800 dark:text-gray-200"/>
                                    </Link>
                                </div>

                                <div className="hidden space-x-2 sm:-my-px sm:ml-5 sm:flex sm:items-center">
                                    <NavLink href={route('dashboard')} active={route().current('dashboard')}>
                                        {t('Dashboard')}
                                    </NavLink>

                                    <NavLink href={route('categories.index')} active={route().current('categories.*')}>
                                        {t('Categories')}
                                    </NavLink>

                                    <NavLink href={route('feeds.index')} active={route().current('feeds.*')}>
                                        {t('Feeds')}
                                    </NavLink>
                                </div>
                            </div>

                            <div className="hidden sm:flex sm:items-center sm:ml-6">
                                <div className="ml-3 relative">
                                    <Dropdown>
                                        <Dropdown.Trigger>
                                            <span className="inline-flex rounded-md">
                                                <button
                                                    type="button"
                                                    className="inline-flex items-center space-x-2 text-gray-500 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700/50 dark:hover:text-white px-3 py-2 rounded-md text-sm transition"
                                                >
                                                    <span>{auth.user.name}</span>

                                                    <DropdownArrowIcon/>
                                                </button>
                                            </span>
                                        </Dropdown.Trigger>

                                        <Dropdown.Content>
                                            <Dropdown.Link
                                                href={route('profile.edit')}
                                                active={route().current('profile.edit')}
                                            >
                                                {t('Profile')}
                                            </Dropdown.Link>
                                            {!!auth.user.is_admin && (
                                                <>
                                                    <Dropdown.Link
                                                        href={route('admin.users.index')}
                                                        active={route().current('admin.users.*')}
                                                    >
                                                        {t('Manage users')}
                                                    </Dropdown.Link>

                                                    <Dropdown.Link
                                                        href={route('telescope')}
                                                        as="a"
                                                    >
                                                        {t('Telescope')}
                                                    </Dropdown.Link>
                                                </>
                                            )}
                                            <Dropdown.Link href={route('logout')} method="post" as="button">
                                                {t('Log Out')}
                                            </Dropdown.Link>
                                        </Dropdown.Content>
                                    </Dropdown>
                                </div>
                            </div>

                            <div className="-mr-2 flex items-center sm:hidden">
                                <button
                                    onClick={() => setShowingNavigationDropdown((previousState) => !previousState)}
                                    className="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out"
                                >
                                    <svg className="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path
                                            className={!showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                        <path
                                            className={showingNavigationDropdown ? 'inline-flex' : 'hidden'}
                                            strokeLinecap="round"
                                            strokeLinejoin="round"
                                            strokeWidth="2"
                                            d="M6 18L18 6M6 6l12 12"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <Transition show={showingNavigationDropdown} as={Fragment} leave="duration-200">
                        <Transition.Child
                            as={Fragment}
                            enter="ease-out duration-300"
                            enterFrom="opacity-0 -translate-y-8 sm:translate-y-0"
                            enterTo="opacity-100 translate-y-0"
                            leave="ease-in duration-200"
                            leaveFrom="opacity-100 translate-y-0"
                            leaveTo="opacity-0 -translate-y-8 sm:translate-y-0"
                        >
                            <div className="fixed overflow-y-auto scrollbar-y-sm pb-16 top-14 bg-white dark:bg-gray-800 z-20 w-full h-full sm:hidden">
                                <div className="pt-2 pb-3 space-y-1">
                                    <ResponsiveNavLink href={route('dashboard')} active={route().current('dashboard')}>
                                        Dashboard
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink href={route('categories.index')} active={route().current('categories.*')}>
                                        {t('Categories')}
                                    </ResponsiveNavLink>

                                    <ResponsiveNavLink href={route('feeds.index')} active={route().current('feeds.*')}>
                                        {t('Feeds')}
                                    </ResponsiveNavLink>
                                </div>

                                <div className="pt-4 pb-1 border-t border-gray-200 dark:border-gray-600">
                                    <div className="px-4">
                                        <div className="font-medium text-base text-gray-800 dark:text-gray-200">
                                            {auth.user.name}
                                        </div>
                                        <div className="font-medium text-sm text-gray-500">{auth.user.email}</div>
                                    </div>

                                    <div className="mt-3 space-y-1">
                                        <ResponsiveNavLink href={route('profile.edit')}>
                                            {t('Profile')}
                                        </ResponsiveNavLink>
                                        {!!auth.user.is_admin && (
                                            <>
                                                <ResponsiveNavLink
                                                    href={route('admin.users.index')}
                                                    active={route().current('admin.users.*')}
                                                >
                                                    {t('Manage users')}
                                                </ResponsiveNavLink>

                                                <ResponsiveNavLink href={route('telescope')} as="a">
                                                    {t('Telescope')}
                                                </ResponsiveNavLink>
                                            </>
                                        )}
                                        <ResponsiveNavLink method="post" href={route('logout')} as="button">
                                            {t('Log Out')}
                                        </ResponsiveNavLink>
                                    </div>
                                </div>
                            </div>
                        </Transition.Child>
                    </Transition>
                </nav>
            </div>

            {(header) && (
                <header className="text-xl max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {header}
                </header>
            )}

            <main className="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                {children}
            </main>
        </div>
    );
}
