import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import CategoriesLoaderType from '@/V2/types/CategoriesLoaderType';

const categoriesLoader: LoaderFunction = async () => {
    return await request('/api/categories').json<CategoriesLoaderType>();
};

export default categoriesLoader;
