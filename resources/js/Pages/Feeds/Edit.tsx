import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {PageProps} from '@/types';
import {RouteParams} from 'ziggy-js';
import {SelectNumberOption} from '@/types/SelectOption';
import {Button} from '@/Components/Button';
import ConfirmAlert from '@/Components/ConfirmAlert';
import {useState} from 'react';
import {Feed} from '@/types/generated/models';

export default function Edit({feed, categories, canDelete, ...props}: PageProps & { feed: Feed; categories: SelectNumberOption[]; canDelete: boolean; }) {
    const {t} = useLaravelReactI18n();
    const {delete: destroy, processing} = useForm();

    const [isConfirmDeleteAlertOpen, setIsConfirmDeleteAlertOpen] = useState(false);

    const handleDelete = () => {
        setIsConfirmDeleteAlertOpen(false);

        destroy(route('feeds.destroy', feed as unknown as RouteParams<'feeds.destroy'>));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            breadcrumbs={props.breadcrumbs}
            actions={(
                <>
                    {canDelete && (
                        <Button
                            disabled={processing}
                            onClick={() => setIsConfirmDeleteAlertOpen(true)}
                            outline
                        >
                            {t('Delete')}
                        </Button>
                    )}

                    <ConfirmAlert
                        open={isConfirmDeleteAlertOpen}
                        title={t('Do you really want to delete this feed?')}
                        confirmTitle={t('Delete feed')}
                        onClose={() => setIsConfirmDeleteAlertOpen(false)}
                        onConfirm={handleDelete}
                    />
                </>
            )}
        >
            <Head title={t('Edit feed')}/>

            <Form
                method="put"
                action={route('feeds.update', feed as unknown as RouteParams<'feeds.update'>)}
                feed={feed}
                categories={categories}
            />
        </AuthenticatedLayout>
    );
}
