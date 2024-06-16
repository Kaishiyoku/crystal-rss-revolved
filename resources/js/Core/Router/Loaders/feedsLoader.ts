import request from '@/Core/request';
import {LoaderFunction} from '@remix-run/router/utils';
import FeedsLoaderType from '@/types/FeedsLoaderType';

const feedsLoader: LoaderFunction = async () => {
    return await request('/api/feeds').json<FeedsLoaderType>();
};

export default feedsLoader;
