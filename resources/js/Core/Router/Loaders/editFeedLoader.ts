import request from '@/Core/request';
import {LoaderFunction} from '@remix-run/router/utils';
import EditFeedLoaderType from '@/types/EditFeddLoaderType';

const editFeedLoader: LoaderFunction = async ({params}) => {
    return await request(`/api/feeds/${params.feedId}/edit`).json<EditFeedLoaderType>();
};

export default editFeedLoader;
