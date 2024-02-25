import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, router} from '@inertiajs/react';
import Header from '@/Components/Page/Header';
import {useState} from 'react';
import FeedItemCard from '@/Components/FeedItemCard';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import FeedFilterDropdown from '@/Components/FeedFilterDropdown';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';
import {TertiaryButton} from '@/Components/Button';
import NewspaperSolidIcon from '@/Icons/NewspaperSolidIcon';
import EmptyState from '@/Components/EmptyState';
import EyeOutlineIcon from '@/Icons/EyeOutlineIcon';
import {PageProps} from '@/types';
import CursorPagination from '@/types/CursorPagination';
import ShortFeed from '@/types/generated/Models/ShortFeed';
import FeedItem from '@/types/generated/Models/FeedItem';

type DashboardPageProps = PageProps & {
    selectedFeed: ShortFeed;
    unreadFeeds: ShortFeed[];
    totalNumberOfFeedItems: number;
    feedItems: CursorPagination<FeedItem>;
};

export default function Dashboard(props: DashboardPageProps) {
    const {t, tChoice} = useLaravelReactI18n();
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);
    const [totalNumberOfFeedItems, setTotalNumberOfFeedItems] = useState(props.totalNumberOfFeedItems);

    const markAllAsRead = async () => {
        await window.ky.put(route('mark-all-as-read'));

        router.get(route('dashboard'));
    };

    const loadMore = () => {
        if (!props.feedItems.next_page_url) {
            return;
        }

        router.get(props.feedItems.next_page_url, undefined, {
            only: ['feedItems'],
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => setAllFeedItems([...allFeedItems, ...(page.props as DashboardPageProps).feedItems.data]),
        });
    };

    return (
        <TotalNumberOfFeedItemsContext.Provider value={{totalNumberOfFeedItems, setTotalNumberOfFeedItems}}>
            <AuthenticatedLayout
                auth={props.auth}
                errors={props.errors}
                header={
                    <Header subTitle={tChoice('dashboard.unread_articles', totalNumberOfFeedItems)}>
                        {t('Dashboard')}
                    </Header>
                }
            >
                <Head title="Dashboard"/>

                <div className="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 pb-8">
                    <FeedFilterDropdown selectedFeed={props.selectedFeed} feeds={props.unreadFeeds}/>

                    {totalNumberOfFeedItems > 0 && (
                        <TertiaryButton
                            confirm
                            confirmTitle={t('Do you really want to mark all articles as read?')}
                            confirmCancelTitle={t('Cancel')}
                            confirmSubmitTitle={t('Mark all articles as read')}
                            onClick={markAllAsRead}
                            icon={EyeOutlineIcon}
                            hasMobileFullSize
                        >
                            {t('Mark all as read')}
                        </TertiaryButton>
                    )}
                </div>

                {allFeedItems.length > 0
                    ? (
                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-16 sm:gap-y-4">
                            {allFeedItems.map((feedItem, index) => (
                                <FeedItemCard
                                    key={feedItem.id}
                                    hueRotationIndex={index % 6}
                                    feedItem={feedItem}
                                />
                            ))}
                        </div>
                    )
                    : (
                        <EmptyState
                            icon={NewspaperSolidIcon}
                            message={t('No unread articles.')}
                            description={t('Come back later.')}
                        />
                    )}

                <div className="pt-5">
                    {props.feedItems.next_page_url && (
                        <button
                            type="button"
                            className="link-secondary"
                            onClick={loadMore}
                        >
                            {t('Load more')}
                        </button>
                    )}
                </div>
            </AuthenticatedLayout>
        </TotalNumberOfFeedItemsContext.Provider>
    );
}
