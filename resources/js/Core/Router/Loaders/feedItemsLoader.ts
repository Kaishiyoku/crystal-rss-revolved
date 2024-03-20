import rq from '@/Core/rq';
import {LoaderFunction} from '@remix-run/router/utils';
import FeedItemsLoaderType from '@/types/FeedItemsLoaderType';

const feedItemsLoader: LoaderFunction = async ({request}) => {
    const searchParams = new URL(request.url).searchParams;
    const cursor = searchParams.get('cursor');
    const feedId = searchParams.get('feed_id');

    const customSearchParams = new URLSearchParams();

    if (cursor) {
        customSearchParams.set('cursor', cursor);
    }

    if (feedId) {
        customSearchParams.set('feed_id', feedId);
    }

    return await rq(`/api/feed-items${customSearchParams.size > 0 ? `?${customSearchParams.toString()}` : ''}`).json<FeedItemsLoaderType>();
};

export default feedItemsLoader;
