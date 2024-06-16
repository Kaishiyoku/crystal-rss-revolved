import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import FeedItemsLoaderType from '@/V2/types/FeedItemsLoaderType';

const feedItemsLoader: LoaderFunction = async ({request: req}) => {
    const cursor = new URL(req.url).searchParams.get('cursor');

    return await request(`/api/feed-items${cursor ? `?cursor=${cursor}` : ''}`).json<FeedItemsLoaderType>();
};

export default feedItemsLoader;
