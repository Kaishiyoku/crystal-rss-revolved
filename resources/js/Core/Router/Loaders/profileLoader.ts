import rq from '@/Core/rq';
import {LoaderFunction} from '@remix-run/router/utils';
import ProfileLoaderType from '@/types/ProfileLoaderType';

const profileLoader: LoaderFunction = async () => {
    return await rq('/api/profile').json<ProfileLoaderType>();
};

export default profileLoader;
