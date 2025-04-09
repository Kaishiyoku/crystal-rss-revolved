import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, router, WhenVisible} from '@inertiajs/react';
import {useEffect, useState} from 'react';
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
import LoadingIcon from '@/Components/Icons/LoadingIcon';

type DashboardPageProps = PageProps & {
    unreadFeeds: ShortFeedWithFeedItemsCount[];
    totalNumberOfFeedItems: number;
    feedItems: CursorPagination<FeedItem>;
};

export default function Dashboard(props: DashboardPageProps) {
    const {t, tChoice} = useLaravelReactI18n();
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);
    const [isFetchingMore, setIsFetchingMore] = useState(false);
    const [isFetchingMoreManually, setIsFetchingMoreManually] = useState(false);
    const [timesLoadedMore, setTimesLoadedMore] = useState(0);

    const totalNumberOfFeedItemsAtomValue = useAtomValue(totalNumberOfFeedItemsAtom);
    const unreadFeedsAtomValue = useAtomValue(unreadFeedsAtom);
    const setUnreadFeedsAtomValue = useSetAtom(unreadFeedsAtom);

    const loadMore = () => {
        if (!props.feedItems.next_page_url) {
            return;
        }

        setIsFetchingMoreManually(true);

        router.get(props.feedItems.next_page_url, undefined, {
            only: ['feedItems', 'unreadFeeds'],
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setUnreadFeedsAtomValue(mergeWithEmptyUnreadFeeds((page.props as PageProps).unreadFeeds, unreadFeedsAtomValue));

                setAllFeedItems([...allFeedItems, ...(page.props as DashboardPageProps).feedItems.data]);

                setTimesLoadedMore((prev) => prev + 1);
            },
            onFinish: () => {
                setIsFetchingMoreManually(false);
            },
        });
    };

    const onBeforeMoreLoading = () => {
        setIsFetchingMore(true);
    };

    const onMoreLoaded = (e: unknown) => {
        const {feedItems, unreadFeeds} = (e as { props: DashboardPageProps; }).props;

        setUnreadFeedsAtomValue(mergeWithEmptyUnreadFeeds(unreadFeeds, unreadFeedsAtomValue));

        setAllFeedItems([...allFeedItems, ...feedItems.data]);

        setTimesLoadedMore((prev) => prev + 1);
    };

    const onMoreFinishedLoading = () => {
        setIsFetchingMore(false);
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

            {props.feedItems.next_cursor !== null && (
                <WhenVisible
                    always={timesLoadedMore < 5}
                    fallback={(
                        <div className="flex max-sm:justify-center pt-6">
                            <LoadingIcon/>
                        </div>
                    )}
                    params={{
                        data: props.selectedFeedId ? {feed_id: props.selectedFeedId, cursor: props.feedItems.next_cursor} : {cursor: props.feedItems.next_cursor},
                        only: ['feedItems', 'unreadFeeds'],
                        onBefore: onBeforeMoreLoading,
                        onSuccess: onMoreLoaded,
                        onFinish: onMoreFinishedLoading,
                    }}
                >
                    <div>
                        {isFetchingMore && (
                            <div className="flex max-sm:justify-center pt-6">
                                <LoadingIcon/>
                            </div>
                        )}
                    </div>
                </WhenVisible>
            )}

            {timesLoadedMore >= 5 && (
                <div className="pt-6">
                    {props.feedItems.next_page_url && (
                        <Button
                            disabled={isFetchingMoreManually}
                            className="max-sm:w-full"
                            onClick={loadMore}
                            plain
                        >
                            <span>{t('Load more')}</span>
                        </Button>
                    )}
                </div>
            )}
        </AuthenticatedLayout>
    );
}
