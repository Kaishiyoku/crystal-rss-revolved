import User from '@/types/generated/Models/User';

type AuthContextType = {
    user: User | null;
    setUser: (user: User | null) => void;
}

export default AuthContextType;
