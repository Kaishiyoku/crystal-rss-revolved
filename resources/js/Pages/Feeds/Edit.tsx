import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {DangerButton} from '@/Components/Button';
import Breadcrumbs from '@/Components/Breadcrumbs/Breadcrumbs';
import {PageProps} from '@/types';
import {RouteParams} from 'ziggy-js';
import {SelectNumberOption} from '@/types/SelectOption';
import Feed from '@/types/generated/Models/Feed';

export default function Edit({feed, categories, canDelete, ...props}: PageProps & { feed: Feed; categories: SelectNumberOption[]; canDelete: boolean; }) {
    const {t} = useLaravelReactI18n();
    const {delete: destroy, processing} = useForm();

    const handleDelete = () => {
        destroy(route('feeds.destroy', feed as unknown as RouteParams<'feeds.destroy'>));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Breadcrumbs breadcrumbs={props.breadcrumbs}/>}
        >
            <Head title={t('Edit feed')}/>

            <Actions>
                {canDelete && (
                    <DangerButton
                        disabled={processing}
                        confirmTitle={t('Do you really want to delete this feed?')}
                        confirmSubmitTitle={t('Delete feed')}
                        confirmCancelTitle={t('Cancel')}
                        onClick={handleDelete}
                    >
                        {t('Delete')}
                    </DangerButton>
                )}
            </Actions>

            <Form
                method="put"
                action={route('feeds.update', feed as unknown as RouteParams<'feeds.update'>)}
                feed={feed}
                categories={categories}
            />
        </AuthenticatedLayout>
    );
}
