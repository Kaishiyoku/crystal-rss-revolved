import rq from '@/Core/rq';
import {LoaderFunction} from '@remix-run/router/utils';
import CategoriesLoaderType from '@/types/CategoriesLoaderType';

const categoriesLoader: LoaderFunction = async () => {
    return await rq('/api/categories').json<CategoriesLoaderType>();
};

export default categoriesLoader;
