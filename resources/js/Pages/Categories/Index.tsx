import {Head, Link} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import LinkStack from '@/Components/LinkStack';
import EmptyState from '@/Components/EmptyState';
import TagOutlineIcon from '@/Icons/TagOutlineIcon';
import {Category, PageProps} from '@/types';
import {RouteParams} from 'ziggy-js';

export default function Index({categories, ...props}: PageProps & { categories: Category[]; }) {
    const {t, tChoice} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Categories')}</Header>}
        >
            <Head title={t('Categories')}/>

            <Actions>
                <Link
                    href={route('categories.create')}
                    className="link-secondary"
                >
                    {t('Add category')}
                </Link>
            </Actions>

            {categories.length > 0
                ? (
                    <LinkStack>
                        {categories.map((category) => (
                            <LinkStack.Item
                                key={category.id}
                                href={route('categories.edit', category as unknown as RouteParams<'categories.edit'>)}
                                className="flex justify-between"
                            >
                                <div>
                                    {category.name}
                                </div>

                                <div className="text-muted">
                                    {tChoice('category.feeds_count', category.feeds_count)}
                                </div>
                            </LinkStack.Item>
                        ))}
                    </LinkStack>
                )
                : (
                    <EmptyState
                        icon={TagOutlineIcon}
                        message={t('No categories.')}
                        description={t('Get started by creating a new category.')}
                    />
                )}
        </AuthenticatedLayout>
    );
}
