import {Head, Link} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Breadcrumbs from '@/Components/Breadcrumbs/Breadcrumbs';
import EmptyState from '@/Components/EmptyState';
import ExclamationCircleOutlineIcon from '@/Icons/ExclamationCircleOutlineIcon';
import PlusOutlineIcon from '@/Icons/PlusOutlineIcon';
import {PageProps} from '@/types';
import {SelectNumberOption} from '@/types/SelectOption';
import Feed from '@/types/generated/Models/Feed';

export default function Create({feed, categories, ...props}: PageProps & { feed: Feed; categories: SelectNumberOption[]; }) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Breadcrumbs breadcrumbs={props.breadcrumbs}/>}
        >
            <Head title={t('Add feed')}/>

            {categories.length > 0
                ? (
                    <Form
                        method="post"
                        action={route('feeds.store')}
                        feed={feed}
                        categories={categories}
                    />
                )
                : (
                    <EmptyState
                        icon={ExclamationCircleOutlineIcon}
                        message={t('Please create a category first.')}
                        description={t('There have to be at least one category before you can create a feed.')}
                    >
                        <Link
                            href={route('categories.create')}
                            className="link-secondary mt-6"
                        >
                            <PlusOutlineIcon className="w-4 h-4 mr-2"/>
                            <div>{t('New category')}</div>
                        </Link>
                    </EmptyState>
                )}
        </AuthenticatedLayout>
    );
}
