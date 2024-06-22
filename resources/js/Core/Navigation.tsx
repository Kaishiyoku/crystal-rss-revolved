import {Avatar} from '@/Components/Avatar';
import {
    Dropdown,
    DropdownButton,
    DropdownDivider,
    DropdownItem,
    DropdownLabel,
    DropdownMenu
} from '@/Components/Dropdown';
import {Navbar, NavbarItem, NavbarSection, NavbarSpacer} from '@/Components/Navbar';
import {
    Sidebar,
    SidebarBody,
    SidebarDivider,
    SidebarFooter,
    SidebarHeader,
    SidebarItem,
    SidebarLabel,
    SidebarSection
} from '@/Components/Sidebar';
import {SidebarLayout} from '@/Components/SidebarLayout';
import {ArrowRightStartOnRectangleIcon, ChevronDownIcon, ChevronUpIcon, UserIcon} from '@heroicons/react/16/solid';
import {HomeIcon, RssIcon} from '@heroicons/react/20/solid';
import {ReactNode} from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import ApplicationLogo from '@/Components/ApplicationLogo';
import User from '@/types/generated/Models/User';
import ShortFeedWithFeedItemsCount from '@/types/generated/Models/ShortFeedWithFeedItemsCount';

export default function Navigation({user, selectedFeedId, unreadFeeds, children}: { user: User; selectedFeedId: number | null; unreadFeeds: ShortFeedWithFeedItemsCount[]; children: ReactNode; }) {
    const {t} = useLaravelReactI18n();

    return (
        <SidebarLayout
            navbar={
                <Navbar>
                    <NavbarSpacer/>
                    <NavbarSection>
                        <Dropdown>
                            <DropdownButton as={NavbarItem}>
                                <Avatar initials={user.name.substring(0, 2)} className="size-10 bg-blue-400 dark:bg-blue-900" square alt=""/>
                            </DropdownButton>
                            <DropdownMenu className="min-w-64" anchor="bottom end">
                                <DropdownItem href={route('profile.edit')}>
                                    <UserIcon/>
                                    <DropdownLabel>{t('Profile')}</DropdownLabel>
                                </DropdownItem>

                                <DropdownDivider/>

                                <DropdownItem href={route('logout')} method="post" as="button">
                                    <ArrowRightStartOnRectangleIcon />
                                    <DropdownLabel>{t('Log Out')}</DropdownLabel>
                                </DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </NavbarSection>
                </Navbar>
            }
            sidebar={
                <Sidebar>
                    <SidebarHeader>
                        <Dropdown>
                            <DropdownButton as={SidebarItem} className="lg:mb-2.5">
                                <ApplicationLogo className="size-5"/>
                                <SidebarLabel>{window.appName}</SidebarLabel>
                                <ChevronDownIcon/>
                            </DropdownButton>
                            <DropdownMenu className="min-w-80 lg:min-w-64" anchor="bottom start">
                                <DropdownItem href={route('categories.index')}>
                                    {t('Categories')}
                                </DropdownItem>

                                <DropdownItem href={route('feeds.index')}>
                                    {t('Feeds')}
                                </DropdownItem>

                                {user.is_admin && (
                                    <>
                                        <DropdownDivider/>

                                        <DropdownItem href={route('admin.users.index')}>
                                            {t('Manage users')}
                                        </DropdownItem>

                                        <DropdownDivider/>

                                        <DropdownItem href={route('horizon.index')} external>
                                            {t('Laravel Horizon')}
                                        </DropdownItem>

                                        <DropdownItem href={route('pulse')} external>
                                            {t('Laravel Pulse')}
                                        </DropdownItem>
                                    </>
                                )}
                            </DropdownMenu>
                        </Dropdown>
                    </SidebarHeader>
                    <SidebarBody>
                        <SidebarSection>
                            <SidebarItem href={route('dashboard')} current={!selectedFeedId && route().current('dashboard')}>
                                <HomeIcon/>
                                <SidebarLabel>
                                    {t('Dashboard')}
                                </SidebarLabel>
                            </SidebarItem>

                            {unreadFeeds.length > 0 && (
                                <>
                                    <SidebarDivider/>

                                    {unreadFeeds.map((unreadFeed) => (
                                        <SidebarItem
                                            key={unreadFeed.id}
                                            href={`${route('dashboard')}?feed_id=${unreadFeed.id}`}
                                            title={unreadFeed.name}
                                            current={selectedFeedId === unreadFeed.id}
                                        >
                                            {unreadFeed.favicon_url
                                                ? <img src={unreadFeed.favicon_url} alt="" loading="lazy" className="size-5 rounded-full"/>
                                                : <RssIcon/>
                                            }

                                            <SidebarLabel>
                                                {unreadFeed.name}
                                            </SidebarLabel>
                                        </SidebarItem>
                                    ))}
                                </>
                            )}
                        </SidebarSection>
                    </SidebarBody>
                    <SidebarFooter className="max-lg:hidden">
                        <Dropdown>
                            <DropdownButton as={SidebarItem}>
                                <span className="flex min-w-0 items-center gap-3">
                                    <Avatar initials={user.name.substring(0, 2)} className="size-10 bg-blue-400 dark:bg-blue-900" square alt=""/>
                                    <span className="min-w-0">
                                        <span className="block truncate text-sm/5 font-medium text-zinc-950 dark:text-white">{user.name}</span>
                                        <span className="block truncate text-xs/5 font-normal text-zinc-500 dark:text-zinc-400">
                                            {user.email}
                                        </span>
                                    </span>
                                </span>
                                <ChevronUpIcon />
                            </DropdownButton>
                            <DropdownMenu className="min-w-64" anchor="top start">
                                <DropdownItem href={route('profile.edit')}>
                                    <UserIcon />
                                    <DropdownLabel>{t('Profile')}</DropdownLabel>
                                </DropdownItem>

                                <DropdownDivider/>

                                <DropdownItem href={route('logout')} method="post" as="button">
                                    <ArrowRightStartOnRectangleIcon />
                                    <DropdownLabel>{t('Log Out')}</DropdownLabel>
                                </DropdownItem>
                            </DropdownMenu>
                        </Dropdown>
                    </SidebarFooter>
                </Sidebar>
            }
        >
            {children}
        </SidebarLayout>
    );
}
