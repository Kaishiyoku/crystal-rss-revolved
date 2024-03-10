import Category from '@/types/generated/Models/Category';
import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';

const categoriesLoader: LoaderFunction = async () => {
    const data = await request('/api/categories').json<{ categories: Category[]; canCreate: boolean; }>();

    return data.categories;
};

export default categoriesLoader;
