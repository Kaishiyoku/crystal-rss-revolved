import {useFetcher, useLoaderData, useNavigate, useSearchParams} from 'react-router-dom';
import FeedItemsLoaderType from '@/types/FeedItemsLoaderType';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {HeadlessButton, TertiaryButton} from '@/Components/Button';
import {useContext, useEffect, useState} from 'react';
import {isEmpty, length} from 'ramda';
import NewspaperSolidIcon from '@/Icons/NewspaperSolidIcon';
import EmptyState from '@/Components/EmptyState';
import request from '@/Core/request';
import FeedItemCard from '@/Components/FeedItemCard';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';
import FeedFilterDropdown from '@/Components/FeedFilterDropdown';
import EyeOutlineIcon from '@/Icons/EyeOutlineIcon';

export default function Home() {
    const {t, tChoice} = useLaravelReactI18n();
    const fetcher = useFetcher();
    const navigate = useNavigate();
    const initialData = useLoaderData() as FeedItemsLoaderType;
    const [searchParams, setSearchParams] = useSearchParams();
    const {totalNumberOfFeedItems, setTotalNumberOfFeedItems} = useContext(TotalNumberOfFeedItemsContext);

    const [data, setData] = useState<FeedItemsLoaderType>(initialData);

    useEffect(() => {
        setTotalNumberOfFeedItems(data.totalNumberOfFeedItems);
    }, []);

    useEffect(() => {
        if (searchParams.has('feed_id')) {
            setData(initialData);
        }
    }, [searchParams.get('feed_id')]);

    useEffect(() => {
        if (searchParams.has('cursor')) {
            return;
        }

        if (isEmpty(initialData.feedItems.data) && searchParams.has('feed_id')) {
            navigate('/');

            return;
        }

        setData(initialData);
    }, [initialData]);

    const handleLoadMore = () => {
        if (!data.feedItems.next_cursor) {
            return;
        }

        const nextSearchParams = new URLSearchParams({...Object.fromEntries(searchParams), cursor: data.feedItems.next_cursor});

        fetcher.load(`/?${nextSearchParams}`);

        setSearchParams(nextSearchParams);
    };

    useEffect(() => {
        if (fetcher.state === 'loading' || fetcher.state === 'submitting') {
            return;
        }

        const fetcherData = fetcher.data as FeedItemsLoaderType;

        if (!fetcherData) {
            return;
        }

        setData((prevState) => ({
            ...fetcherData,
            feedItems: {
                ...fetcherData.feedItems,
                data: [...prevState.feedItems.data, ...fetcherData.feedItems.data],
            },
        }));
    }, [fetcher]);

    const markAllAsRead = async () => {
        await request.put('/feeds/mark-all-as-read');

        setTotalNumberOfFeedItems(0);

        navigate('/');
    };

    return (
        <div>
            <div className="pb-4">
                {tChoice('dashboard.unread_articles', totalNumberOfFeedItems)}
            </div>

            <div className="flex flex-col sm:flex-row sm:space-x-2 space-y-2 sm:space-y-0 pb-8">
                {data.feedItems && <FeedFilterDropdown selectedFeed={data.selectedFeed} feeds={data.unreadFeeds}/>}

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

            {length(data.feedItems.data) > 0
                ? (
                    <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-16 sm:gap-y-4">
                        {data.feedItems.data.map((feedItem, index) => (
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

            {data.feedItems.next_cursor && (
                <HeadlessButton className="link-secondary mt-8" onClick={handleLoadMore}>
                    {t('Load more')}
                </HeadlessButton>
            )}
        </div>
    );
}
