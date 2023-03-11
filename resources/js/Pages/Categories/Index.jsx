import {Head, Link} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import LinkListCardContainer from '@/Components/LinkListCardContainer';
import CardLink from '@/Components/CardLink';
import EmptyState from '@/Components/EmptyState';
import TagOutlineIcon from '@/Icons/TagOutlineIcon';

/**
 * @param {Category[]} categories
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export default function Index({categories, ...props}) {
    const {t, tChoice} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Categories')}</Header>}
        >
            <Head title={t('Categories')}/>

            <Actions withMobileSpacing>
                <Link
                    href={route('categories.create')}
                    className="link-secondary"
                >
                    {t('Add category')}
                </Link>
            </Actions>

            {categories.length > 0 ? (
                <LinkListCardContainer>
                    {categories.map((category) => (
                        <CardLink
                            key={category.id}
                            href={route('categories.edit', category)}
                            className="flex justify-between"
                        >
                            <div>
                                {category.name}
                            </div>

                            <div className="text-muted">
                                {tChoice('category.feeds_count', category.feeds_count)}
                            </div>
                        </CardLink>
                    ))}
                </LinkListCardContainer>
            ) : (
                <EmptyState
                    icon={TagOutlineIcon}
                    message={t('No categories.')}
                    description={t('Get started by creating a new category.')}
                />
            )}
        </AuthenticatedLayout>
    );
}
