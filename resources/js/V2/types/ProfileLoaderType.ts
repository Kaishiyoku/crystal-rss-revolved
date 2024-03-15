import User from '@/types/generated/Models/User';

type ProfileLoaderType = {
    mustVerifyEmail: boolean;
    status: string;
    user: User;
}
export default ProfileLoaderType;
