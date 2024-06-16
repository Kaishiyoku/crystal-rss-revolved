import request from '@/Core/request';
import {LoaderFunction} from '@remix-run/router/utils';
import CategoriesLoaderType from '@/types/CategoriesLoaderType';

const categoriesLoader: LoaderFunction = async () => {
    return await request('/api/categories').json<CategoriesLoaderType>();
};

export default categoriesLoader;
