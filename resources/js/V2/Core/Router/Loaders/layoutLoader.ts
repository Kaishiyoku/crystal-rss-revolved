import request from '@/V2/request';
import User from '@/types/generated/Models/User';
import {LoaderFunction} from '@remix-run/router/utils';

const layoutLoader: LoaderFunction = async () => {
    return await request('/api/user').json<User>();
};

export default layoutLoader;
