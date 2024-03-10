import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import CategoriesLoaderType from '@/V2/types/CategoriesLoaderType';

const categoryLoader = (suffix?: string): LoaderFunction => async ({params}) => {
    return await request(`/api/categories/${params.categoryId}${suffix}`).json<CategoriesLoaderType>();
};

export default categoryLoader;
