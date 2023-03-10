import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Categories/Partials/Form';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {DangerButton} from '@/Components/Button';
import BreadcrumbsContainer from '@/Components/Breadcrumbs/BreadcrumbsContainer';

export default function Edit(props) {
    const {t} = useLaravelReactI18n();
    const {delete: destroy, processing} = useForm();

    const handleDelete = () => {
        destroy(route('categories.destroy', props.category));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<BreadcrumbsContainer breadcrumbs={props.breadcrumbs}/>}
            withMobileSpacing
        >
            <Head title={t('Edit category')}/>

            <Actions>
                {props.canDelete && (
                    <DangerButton disabled={processing} onClick={handleDelete}>
                        {t('Delete')}
                    </DangerButton>
                )}
            </Actions>

            <Form method="put" action={route('categories.update', props.category)} category={props.category}/>
        </AuthenticatedLayout>
    );
}
