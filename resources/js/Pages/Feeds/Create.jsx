import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import BreadcrumbsContainer from '@/Components/Breadcrumbs/BreadcrumbsContainer';

export default function Create(props) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<BreadcrumbsContainer breadcrumbs={props.breadcrumbs}/>}
            withMobileSpacing
        >
            <Head title={t('Add feed')}/>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <Form
                        method="post"
                        action={route('feeds.store')}
                        feed={props.feed}
                        categories={props.categories}
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
