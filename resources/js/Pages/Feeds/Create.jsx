import {Head, Link} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Breadcrumbs from '@/Components/Breadcrumbs/Breadcrumbs';
import EmptyState from '@/Components/EmptyState';
import ExclamationCircleOutlineIcon from '@/Icons/ExclamationCircleOutlineIcon';
import {PrimaryButton} from '@/Components/Button';
import PlusOutlineIcon from '@/Icons/PlusOutlineIcon';

export default function Create(props) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Breadcrumbs breadcrumbs={props.breadcrumbs}/>}
            hasMobileSpacing
        >
            <Head title={t('Add feed')}/>

            {props.categories.length > 0 ? (
                <Form
                    method="post"
                    action={route('feeds.store')}
                    feed={props.feed}
                    categories={props.categories}
                />
            ) : (
                <EmptyState
                    icon={ExclamationCircleOutlineIcon}
                    message={t('Please create a category first.')}
                    description={t('There have to be at least one category before you can create a feed.')}
                >
                    <PrimaryButton as={Link} href={route('categories.create')} className="mt-6">
                        <PlusOutlineIcon className="w-4 h-4 mr-2"/>
                        <div>{t('New category')}</div>
                    </PrimaryButton>
                </EmptyState>
            )}
        </AuthenticatedLayout>
    );
}
