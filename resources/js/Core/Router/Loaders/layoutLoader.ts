import rq from '@/Core/rq';
import User from '@/types/generated/Models/User';
import {LoaderFunction} from '@remix-run/router/utils';
import {HTTPError} from 'ky';

const layoutLoader: LoaderFunction = async () => {
    try {
        return await rq('/api/user').json<User>();
    } catch (error) {
        const errorResponse = (error as HTTPError).response;

        if (errorResponse.status === 401) {
            window.location.href = '/login';

            return null;
        }

        throw error;
    }
};

export default layoutLoader;
