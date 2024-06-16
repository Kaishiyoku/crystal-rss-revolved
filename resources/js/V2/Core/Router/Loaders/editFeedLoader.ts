import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import EditFeedLoaderType from '@/V2/types/EditFeddLoaderType';

const editFeedLoader: LoaderFunction = async ({params}) => {
    return await request(`/api/feeds/${params.feedId}/edit`).json<EditFeedLoaderType>();
};

export default editFeedLoader;
