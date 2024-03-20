import rq from '@/Core/rq';
import {LoaderFunction} from '@remix-run/router/utils';
import FeedsLoaderType from '@/types/FeedsLoaderType';

const feedsLoader: LoaderFunction = async () => {
    return await rq('/api/feeds').json<FeedsLoaderType>();
};

export default feedsLoader;
