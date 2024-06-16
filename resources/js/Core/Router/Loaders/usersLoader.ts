import request from '@/Core/request';
import {LoaderFunction} from '@remix-run/router/utils';
import UsersLoaderType from '@/types/UsersLoaderType';
import {redirect} from 'react-router-dom';
import {HTTPError} from 'ky';

const usersLoader: LoaderFunction = async () => {
    try {
        return await request('/api/admin/users').json<UsersLoaderType>();
    } catch (error) {
        const errorResponse = (error as HTTPError).response;

        if (errorResponse.status === 401) {
            window.location.href = '/login';

            return null;
        }

        return redirect('/');
    }
};

export default usersLoader;
