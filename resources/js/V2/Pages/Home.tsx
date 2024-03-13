import {Location, useFetcher, useLoaderData, useLocation, useNavigate, useSearchParams} from 'react-router-dom';
import FeedItemsLoaderType from '@/V2/types/FeedItemsLoaderType';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {HeadlessButton} from '@/Components/Button';
import {useEffect, useState} from 'react';
import FeedItem from '@/types/generated/Models/FeedItem';
import CursorPagination from '@/types/CursorPagination';
import {length} from 'ramda';
import NewspaperSolidIcon from '@/Icons/NewspaperSolidIcon';
import EmptyState from '@/Components/EmptyState';
import Actions from '@/Components/Actions';
import request from '@/V2/request';
import HomeLocationState from '@/V2/types/HomeLocationState';

export default function Home() {
    const {t, tChoice} = useLaravelReactI18n();
    const fetcher = useFetcher();
    const navigate = useNavigate();
    const location: Location<HomeLocationState> = useLocation();
    const {feedItems: initialFeedItems, totalNumberOfFeedItems} = useLoaderData() as FeedItemsLoaderType;
    const [searchParams, setSearchParams] = useSearchParams();

    const [feedItems, setFeedItems] = useState<CursorPagination<FeedItem>>(initialFeedItems);

    const handleLoadMore = () => {
        if (!feedItems.next_cursor) {
            return;
        }

        setSearchParams({cursor: feedItems.next_cursor});

        fetcher.load(`/?cursor=${feedItems.next_cursor}`);
    };

    useEffect(() => {
        if (fetcher.state === 'loading' || fetcher.state === 'submitting') {
            return;
        }

        const fetcherData = fetcher.data as FeedItemsLoaderType;

        if (!fetcherData) {
            return;
        }

        setFeedItems((prevState) => ({
            ...fetcherData.feedItems,
            data: [...prevState.data, ...fetcherData.feedItems.data],
        }));
    }, [fetcher]);

    const markAllAsRead = async () => {
        await request.put('/feeds/mark-all-as-read');

        navigate(0);
    };

    return (
        <div>
            <div className="text-lg pb-4">
                {tChoice('dashboard.unread_articles', totalNumberOfFeedItems)}
            </div>

            <Actions>
                {totalNumberOfFeedItems > 0 && (
                    <HeadlessButton
                        confirm
                        confirmTitle={t('Do you really want to mark all articles as read?')}
                        confirmCancelTitle={t('Cancel')}
                        confirmSubmitTitle={t('Mark all articles as read')}
                        onClick={markAllAsRead}
                        className="link-secondary"
                        hasMobileFullSize
                    >
                        {t('Mark all as read')}
                    </HeadlessButton>
                )}
            </Actions>

            {length(feedItems.data) > 0
                ? (
                    <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-16 sm:gap-y-4">
                        {feedItems.data.map((feedItem) => (
                            <div key={feedItem.id}>{feedItem.title}</div>
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

            {feedItems.next_cursor && (
                <HeadlessButton className="link-secondary" onClick={handleLoadMore}>
                    {t('Load more')}
                </HeadlessButton>
            )}
        </div>
    );
}
