import rq from '@/Core/rq';
import {LoaderFunction} from '@remix-run/router/utils';
import EditCategoryLoaderType from '@/types/EditCategoryLoaderType';

const editCategoryLoader: LoaderFunction = async ({params}) => {
    return await rq(`/api/categories/${params.categoryId}/edit`).json<EditCategoryLoaderType>();
};

export default editCategoryLoader;
