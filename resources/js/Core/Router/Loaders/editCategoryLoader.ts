import request from '@/Core/request';
import {LoaderFunction} from '@remix-run/router/utils';
import EditCategoryLoaderType from '@/types/EditCategoryLoaderType';

const editCategoryLoader: LoaderFunction = async ({params}) => {
    return await request(`/api/categories/${params.categoryId}/edit`).json<EditCategoryLoaderType>();
};

export default editCategoryLoader;
