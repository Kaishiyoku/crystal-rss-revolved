import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import FeedsLoaderType from '@/V2/types/FeedsLoaderType';

const feedsLoader: LoaderFunction = async () => {
    return await request('/api/feeds').json<FeedsLoaderType>();
};

export default feedsLoader;
