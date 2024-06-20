import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, router} from '@inertiajs/react';
import Header from '@/Components/Page/Header';
import {useState} from 'react';
import FeedItemCard from '@/Components/FeedItemCard';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';
import {Button} from '@/Components/Button';
import NewspaperSolidIcon from '@/Icons/NewspaperSolidIcon';
import EmptyState from '@/Components/EmptyState';
import {PageProps} from '@/types';
import CursorPagination from '@/types/CursorPagination';
import FeedItem from '@/types/generated/Models/FeedItem';
import ShortFeedWithFeedItemsCount from '@/types/generated/Models/ShortFeedWithFeedItemsCount';
import MarkAllAsReadButton from '@/Components/MarkAllAsReadButton';

type DashboardPageProps = PageProps & {
    unreadFeeds: ShortFeedWithFeedItemsCount[];
    totalNumberOfFeedItems: number;
    feedItems: CursorPagination<FeedItem>;
};

export default function Dashboard(props: DashboardPageProps) {
    const {t, tChoice} = useLaravelReactI18n();
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);
    const [totalNumberOfFeedItems, setTotalNumberOfFeedItems] = useState(props.totalNumberOfFeedItems);

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
                    {totalNumberOfFeedItems > 0 && (
                        <MarkAllAsReadButton/>
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
                        <Button
                            onClick={loadMore}
                            plain
                        >
                            {t('Load more')}
                        </Button>
                    )}
                </div>
            </AuthenticatedLayout>
        </TotalNumberOfFeedItemsContext.Provider>
    );
}
