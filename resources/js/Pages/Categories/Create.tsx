import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Categories/Partials/Form';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Breadcrumbs from '@/Components/Breadcrumbs/Breadcrumbs';
import {PageProps} from '@/types';
import Category from '@/types/generated/Models/Category';

export default function Create({category, ...props}: PageProps & { category: Category; }) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Breadcrumbs breadcrumbs={props.breadcrumbs}/>}
        >
            <Head title={t('Add category')}/>

            <Form
                method="post" action={route('categories.store')}
                category={category}
            />
        </AuthenticatedLayout>
    );
}
