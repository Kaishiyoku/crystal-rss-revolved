import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import ProfileLoaderType from '@/V2/types/ProfileLoaderType';

const profileLoader: LoaderFunction = async () => {
    return await request('/api/profile').json<ProfileLoaderType>();
};

export default profileLoader;
