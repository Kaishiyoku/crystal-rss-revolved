import Category from '@/types/generated/Models/Category';
import {useLoaderData} from 'react-router-dom';

export default function CategoriesIndex() {
    const categories = useLoaderData() as Category[];

    return (
        <div>
            <div>
                {categories.map((category) => (
                    <div key={category.id}>{category.name}</div>
                ))}
            </div>
        </div>
    );
}
