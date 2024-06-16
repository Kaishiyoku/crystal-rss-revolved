import {ReactElement, useState} from 'react';
import User from '@/types/generated/Models/User';
import AuthContext from '@/V2/Contexts/AuthContext';

export default function AuthProvider({children}: { children: ReactElement; }) {
    const [user, setUser] = useState<User | null>(null);

    return (
        <AuthContext.Provider value={{user, setUser}}>
            {children}
        </AuthContext.Provider>
    );
}
