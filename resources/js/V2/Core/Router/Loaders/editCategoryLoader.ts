import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';
import CategoriesLoaderType from '@/V2/types/CategoriesLoaderType';
import EditCategoryLoaderType from '@/V2/types/EditCategoryLoaderType';

const editCategoryLoader: LoaderFunction = async ({params}) => {
    return await request(`/api/categories/${params.categoryId}/edit`).json<EditCategoryLoaderType>();
};

export default editCategoryLoader;
