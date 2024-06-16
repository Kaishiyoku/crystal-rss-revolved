import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import DeleteUserForm from './Partials/DeleteUserForm';
import UpdatePasswordForm from './Partials/UpdatePasswordForm';
import UpdateProfileInformationForm from './Partials/UpdateProfileInformationForm';
import {Head} from '@inertiajs/react';
import Header from '@/Components/Page/Header';
import {BasePageProps} from '@/types';

export default function Edit({auth, errors, mustVerifyEmail, status}: BasePageProps & { mustVerifyEmail: boolean; status: string; }) {
    return (
        <AuthenticatedLayout
            auth={auth}
            header={<Header>Profile</Header>}
            errors={errors}
        >
            <Head title="Profile"/>

            <div className="space-y-8">
                <UpdateProfileInformationForm
                    mustVerifyEmail={mustVerifyEmail}
                    status={status}
                />

                <UpdatePasswordForm/>

                <DeleteUserForm/>
            </div>
        </AuthenticatedLayout>
    );
}
