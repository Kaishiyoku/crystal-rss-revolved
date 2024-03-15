import request from '@/Core/request';
import {LoaderFunction} from '@remix-run/router/utils';
import ProfileLoaderType from '@/types/ProfileLoaderType';

const profileLoader: LoaderFunction = async () => {
    return await request('/api/profile').json<ProfileLoaderType>();
};

export default profileLoader;
