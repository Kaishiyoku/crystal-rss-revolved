import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {DangerButton} from '@/Components/Button';
import Breadcrumbs from '@/Components/Breadcrumbs/Breadcrumbs';
import {Feed, PageProps, SelectNumberOption} from '@/types';
import {RouteParams} from 'ziggy-js';

export default function Edit({feed, categories, canDelete, ...props}: PageProps & { feed: Feed; categories: SelectNumberOption[]; canDelete: boolean; }) {
    const {t} = useLaravelReactI18n();
    const {delete: destroy, processing} = useForm();

    const handleDelete = () => {
        destroy(route('feeds.destroy', props.feed as unknown as RouteParams<'feeds.destroy'>));
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
                    <DangerButton disabled={processing} onClick={handleDelete} className="mb-5">
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
