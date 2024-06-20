import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, router, usePage} from '@inertiajs/react';
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
import {head} from 'ramda';

type DashboardPageProps = PageProps & {
    unreadFeeds: ShortFeedWithFeedItemsCount[];
    totalNumberOfFeedItems: number;
    feedItems: CursorPagination<FeedItem>;
};

export default function Dashboard(props: DashboardPageProps) {
    const {t, tChoice} = useLaravelReactI18n();
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);
    const [totalNumberOfFeedItems, setTotalNumberOfFeedItems] = useState(props.totalNumberOfFeedItems);

    const {selectedFeedId, unreadFeeds} = usePage<PageProps>().props;

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
                    <>
                        {t('Dashboard')}

                        <small className="text-muted pl-2">{tChoice('dashboard.unread_articles', totalNumberOfFeedItems)}</small>
                    </>
                }
                actions={(
                    <>
                        {totalNumberOfFeedItems > 0 && (
                            <MarkAllAsReadButton/>
                        )}
                    </>
                )}
            >
                <Head title="Dashboard"/>

                {allFeedItems.length > 0
                    ? (
                        <div className="grid sm:grid-cols-2 gap-6">
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
