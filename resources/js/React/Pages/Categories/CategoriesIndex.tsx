import Category from '@/types/generated/Models/Category';
import {Link, Outlet, useLoaderData} from 'react-router-dom';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Actions from '@/Components/Actions';

export default function CategoriesIndex() {
    const categories = useLoaderData() as Category[];
    const {t} = useLaravelReactI18n();

    return (
        <div>
            <Actions>
                <Link to="/react/categories/create" className="link-secondary">
                    {t('Add category')}
                </Link>
            </Actions>

            <div className="pt-4">
                {categories.map((category) => (
                    <div key={category.id}>{category.name}</div>
                ))}
            </div>

            <Outlet/>
        </div>
    );
}
