import {useLoaderData} from 'react-router-dom';
import UpdateProfileInformation from '@/Pages/Profile/Partials/UpdateProfileInformationForm';
import ProfileLoaderType from '@/V2/types/ProfileLoaderType';

export default function ProfilePage() {
    const {mustVerifyEmail, status, user} = useLoaderData() as ProfileLoaderType;

    return (
        <div>
            <UpdateProfileInformation
                mustVerifyEmail={mustVerifyEmail}
                status={status}
                user={user}
            />
        </div>
    );
}
