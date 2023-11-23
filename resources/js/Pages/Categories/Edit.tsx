import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Categories/Partials/Form';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {DangerButton} from '@/Components/Button';
import Breadcrumbs from '@/Components/Breadcrumbs/Breadcrumbs';
import {PageProps} from '@/types';
import {RouteParams} from 'ziggy-js';
import Category from '@/types/Models/Category';

export default function Edit({category, canDelete, ...props}: PageProps & { category: Category; canDelete: boolean; }) {
    const {t} = useLaravelReactI18n();
    const {delete: destroy, processing} = useForm();

    const handleDelete = () => {
        destroy(route('categories.destroy', category as unknown as RouteParams<'categories.destroy'>));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Breadcrumbs breadcrumbs={props.breadcrumbs}/>}
        >
            <Head title={t('Edit category')}/>

            <Actions>
                {canDelete && (
                    <DangerButton
                        disabled={processing}
                        confirmTitle={t('Do you really want to delete this category?')}
                        confirmSubmitTitle={t('Delete category')}
                        confirmCancelTitle={t('Cancel')}
                        onClick={handleDelete}
                    >
                        {t('Delete')}
                    </DangerButton>
                )}
            </Actions>

            <Form
                method="put"
                action={route('categories.update', category as unknown as RouteParams<'categories.update'>)}
                category={category}
            />
        </AuthenticatedLayout>
    );
}
