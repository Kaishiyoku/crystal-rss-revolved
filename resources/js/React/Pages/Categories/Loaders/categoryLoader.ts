import Category from '@/types/generated/Models/Category';
import request from '@/React/request';
import {Params} from 'react-router-dom';

const categoryLoader = (suffix?: string) => async ({params}: { params: Params; }) => {
    const data = await request(`/api/categories/${params.categoryId}${suffix}`).json<{ category: Category; canDelete: boolean; }>();

    return data.category;
};

export default categoryLoader;
