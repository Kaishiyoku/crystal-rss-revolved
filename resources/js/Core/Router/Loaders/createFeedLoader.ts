import rq from '@/Core/rq';
import {LoaderFunction} from '@remix-run/router/utils';
import CreateFeedLoaderType from '@/types/CreateFeedLoaderType';

const createFeedLoader: LoaderFunction = async () => {
    return await rq('/api/feeds/create').json<CreateFeedLoaderType>();
};

export default createFeedLoader;
