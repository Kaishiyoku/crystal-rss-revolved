import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Categories/Partials/Form';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Breadcrumbs from '@/Components/Breadcrumbs/Breadcrumbs';

export default function Create(props) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Breadcrumbs breadcrumbs={props.breadcrumbs}/>}
        >
            <Head title={t('Add category')}/>

            <Form method="post" action={route('categories.store')} category={props.category}/>
        </AuthenticatedLayout>
    );
}
