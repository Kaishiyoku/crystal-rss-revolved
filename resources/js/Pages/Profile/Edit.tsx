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

            <div className="mb-8 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg">
                <UpdateProfileInformationForm
                    mustVerifyEmail={mustVerifyEmail}
                    status={status}
                    className="max-w-xl"
                />
            </div>

            <div className="mb-8 p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg">
                <UpdatePasswordForm className="max-w-xl"/>
            </div>

            <div className="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow rounded-lg">
                <DeleteUserForm className="max-w-xl"/>
            </div>
        </AuthenticatedLayout>
    );
}
