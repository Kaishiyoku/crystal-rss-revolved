import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, router} from '@inertiajs/react';
import {useState} from 'react';
import FeedItemCard from '@/Components/FeedItemCard';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Button} from '@/Components/Button';
import {EmptyState} from '@/Components/EmptyState';
import {PageProps} from '@/types';
import CursorPagination from '@/types/CursorPagination';
import MarkAllAsReadButton from '@/Components/MarkAllAsReadButton';
import {NewspaperIcon} from '@heroicons/react/24/outline';
import ShortFeedWithFeedItemsCount from '@/types/models/ShortFeedWithFeedItemsCount';
import {FeedItem} from '@/types/generated/models';
import {useAtomValue, useSetAtom} from 'jotai';
import {mergeWithEmptyUnreadFeeds, totalNumberOfFeedItemsAtom, unreadFeedsAtom} from '@/Stores/unreadFeedsAtom';

type DashboardPageProps = PageProps & {
    unreadFeeds: ShortFeedWithFeedItemsCount[];
    totalNumberOfFeedItems: number;
    feedItems: CursorPagination<FeedItem>;
};

export default function Dashboard(props: DashboardPageProps) {
    const {t, tChoice} = useLaravelReactI18n();
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);

    const totalNumberOfFeedItemsAtomValue = useAtomValue(totalNumberOfFeedItemsAtom);
    const unreadFeedsAtomValue = useAtomValue(unreadFeedsAtom);
    const setUnreadFeedsAtomValue = useSetAtom(unreadFeedsAtom);

    const loadMore = () => {
        if (!props.feedItems.next_page_url) {
            return;
        }

        router.get(props.feedItems.next_page_url, undefined, {
            only: ['feedItems', 'unreadFeeds'],
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setUnreadFeedsAtomValue(mergeWithEmptyUnreadFeeds((page.props as PageProps).unreadFeeds, unreadFeedsAtomValue));

                setAllFeedItems([...allFeedItems, ...(page.props as DashboardPageProps).feedItems.data]);
            },
        });
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={
                <>
                    {t('Dashboard')}

                    <small className="text-muted pl-2">{tChoice('dashboard.unread_articles', totalNumberOfFeedItemsAtomValue)}</small>
                </>
            }
            actions={(
                <>
                    {totalNumberOfFeedItemsAtomValue > 0 && (
                        <MarkAllAsReadButton/>
                    )}
                </>
            )}
        >
            <Head title="Dashboard"/>

            {allFeedItems.length > 0
                ? (
                    <div className="grid sm:grid-cols-2 gap-6">
                        {allFeedItems.map((feedItem) => (
                            <FeedItemCard
                                key={feedItem.id}
                                feedItem={feedItem}
                            />
                        ))}
                    </div>
                )
                : (
                    <EmptyState
                        icon={NewspaperIcon}
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
    );
}
