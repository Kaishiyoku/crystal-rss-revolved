import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import FeedItemsLoaderType from '@/V2/types/FeedItemsLoaderType';

const feedItemsLoader: LoaderFunction = async ({request: req}) => {
    const searchParams = new URL(req.url).searchParams;
    const cursor = searchParams.get('cursor');
    const feedId = searchParams.get('feed_id');

    const customSearchParams = new URLSearchParams();

    if (cursor) {
        customSearchParams.set('cursor', cursor);
    }

    if (feedId) {
        customSearchParams.set('feed_id', feedId);
    }

    return await request(`/api/feed-items${customSearchParams.size > 0 ? `?${customSearchParams.toString()}` : ''}`).json<FeedItemsLoaderType>();
};

export default feedItemsLoader;
