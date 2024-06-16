import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import CreateFeedLoaderType from '@/V2/types/CreateFeedLoaderType';

const createFeedLoader: LoaderFunction = async () => {
    return await request('/api/feeds/create').json<CreateFeedLoaderType>();
};

export default createFeedLoader;
