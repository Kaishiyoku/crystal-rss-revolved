import Category from '@/types/generated/Models/Category';
import request from '@/V2/request';
import {LoaderFunction} from '@remix-run/router/utils';

const categoryLoader = (suffix?: string): LoaderFunction => async ({params}) => {
    const data = await request(`/api/categories/${params.categoryId}${suffix}`).json<{ category: Category; canDelete: boolean; }>();

    return data.category;
};

export default categoryLoader;
