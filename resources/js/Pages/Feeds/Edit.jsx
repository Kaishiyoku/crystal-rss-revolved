import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Form from '@/Pages/Feeds/Partials/Form';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {DangerButton} from '@/Components/Button';

export default function Edit(props) {
    const {t} = useLaravelReactI18n();
    const {delete: destroy, processing} = useForm();

    const handleDelete = () => {
        destroy(route('feeds.destroy', props.feed));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Edit feed')}</Header>}
            withMobileSpacing
        >
            <Head title={t('Edit feed')}/>

            <Actions>
                {props.canDelete && (
                    <DangerButton disabled={processing} onClick={handleDelete} className="mb-5">
                        {t('Delete')}
                    </DangerButton>
                )}
            </Actions>

            <Form
                method="put"
                action={route('feeds.update', props.feed)}
                feed={props.feed}
                categories={props.categories}
            />
        </AuthenticatedLayout>
    );
}
