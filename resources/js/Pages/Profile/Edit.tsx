import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import {Head} from '@inertiajs/react';
import {BasePageProps} from '@/types';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {DividerContainer} from '@/Components/Divider';

export default function Edit({auth, errors, mustVerifyEmail, status}: BasePageProps & { mustVerifyEmail: boolean; status: string; }) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={auth}
            header={t('Profile')}
            errors={errors}
        >
            <Head title="Profile"/>

            <DividerContainer>
                <UpdateProfileInformationForm
                    mustVerifyEmail={mustVerifyEmail}
                    status={status}
                    className="max-w-xl"
                />

                <UpdatePasswordForm className="max-w-xl"/>

                <DeleteUserForm className="max-w-xl"/>
            </DividerContainer>
        </AuthenticatedLayout>
    );
}
