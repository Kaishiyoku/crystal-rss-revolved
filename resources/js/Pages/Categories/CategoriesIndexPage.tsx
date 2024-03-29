import {Link, Outlet, useLoaderData} from 'react-router-dom';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Actions from '@/Components/Actions';
import LinkStack from '@/Components/LinkStack';
import {isEmpty} from 'ramda';
import EmptyState from '@/Components/EmptyState';
import TagOutlineIcon from '@/Icons/TagOutlineIcon';
import CategoriesLoaderType from '@/types/CategoriesLoaderType';

export default function CategoriesIndexPage() {
    const {categories, canCreate} = useLoaderData() as CategoriesLoaderType;
    const {t, tChoice} = useLaravelReactI18n();

    return (
        <div>
            <Actions>
                {canCreate && (
                    <Link to="/categories/create" className="link-secondary">
                        {t('Add category')}
                    </Link>
                )}
            </Actions>

            {isEmpty(categories)
                ? (
                    <EmptyState
                        icon={TagOutlineIcon}
                        message={t('No categories.')}
                        description={t('Get started by creating a new category.')}
                    />
                )
                : (
                    <LinkStack>
                        {categories.map((category) => (
                            <LinkStack.Item
                                key={category.id}
                                to={`/categories/${category.id}/edit`}
                                className="block"
                            >
                                <div className="font-semibold">
                                    {category.name}
                                </div>

                                <div className="text-sm text-muted">
                                    {tChoice('category.feeds_count', category.feeds_count)}
                                </div>
                            </LinkStack.Item>
                        ))}
                    </LinkStack>
                )}

            <Outlet/>
        </div>
    );
}
