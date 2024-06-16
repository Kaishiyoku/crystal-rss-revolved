import request from '@/Core/request';
import {LoaderFunction} from '@remix-run/router/utils';
import CreateFeedLoaderType from '@/types/CreateFeedLoaderType';

const createFeedLoader: LoaderFunction = async () => {
    return await request('/api/feeds/create').json<CreateFeedLoaderType>();
};

export default createFeedLoader;
