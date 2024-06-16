import {useFetcher, useLoaderData, useSearchParams} from 'react-router-dom';
import FeedItemsLoaderType from '@/V2/types/FeedItemsLoaderType';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {HeadlessButton} from '@/Components/Button';
import {useEffect, useState} from 'react';
import FeedItem from '@/types/generated/Models/FeedItem';
import CursorPagination from '@/types/CursorPagination';

export default function Home() {
    const {t} = useLaravelReactI18n();
    const fetcher = useFetcher();
    const {feedItems: initialFeedItems} = useLoaderData() as FeedItemsLoaderType;
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

    return (
        <div>
            {feedItems.data.map((feedItem) => (
                <div key={feedItem.id}>{feedItem.title}</div>
            ))}

            {feedItems.next_cursor && (
                <HeadlessButton className="link-secondary" onClick={handleLoadMore}>
                    {t('Load more')}
                </HeadlessButton>
            )}
        </div>
    );
}
