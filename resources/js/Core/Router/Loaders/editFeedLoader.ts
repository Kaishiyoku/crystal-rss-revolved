import rq from '@/Core/rq';
import {LoaderFunction} from '@remix-run/router/utils';
import EditFeedLoaderType from '@/types/EditFeddLoaderType';

const editFeedLoader: LoaderFunction = async ({params}) => {
    return await rq(`/api/feeds/${params.feedId}/edit`).json<EditFeedLoaderType>();
};

export default editFeedLoader;
