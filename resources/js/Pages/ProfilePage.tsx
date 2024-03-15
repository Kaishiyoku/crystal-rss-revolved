import {useLoaderData} from 'react-router-dom';
import UpdateProfileInformation from '@/Pages/Profile/Partials/UpdateProfileInformationForm';
import ProfileLoaderType from '@/types/ProfileLoaderType';
import UpdatePasswordForm from '@/Pages/Profile/Partials/UpdatePasswordForm';
import DeleteUserForm from '@/Pages/Profile/Partials/DeleteUserForm';

export default function ProfilePage() {
    const {mustVerifyEmail, status, user} = useLoaderData() as ProfileLoaderType;

    return (
        <div className="space-y-8">
            <UpdateProfileInformation
                mustVerifyEmail={mustVerifyEmail}
                status={status}
                user={user}
            />

            <UpdatePasswordForm/>

            <DeleteUserForm/>
        </div>
    );
}
