import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import FeedsLoaderType from '@/V2/types/FeedsLoaderType';

const usersLoader: LoaderFunction = async () => {
    return await request('/api/admin/users').json<FeedsLoaderType>();
};

export default usersLoader;
