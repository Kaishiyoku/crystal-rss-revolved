import Category from '@/types/generated/Models/Category';
import request from '@/React/request';

export default async function categoriesLoader() {
    const data = await request('/api/categories').json<{ categories: Category[]; canCreate: boolean; }>();

    return data.categories;
}
