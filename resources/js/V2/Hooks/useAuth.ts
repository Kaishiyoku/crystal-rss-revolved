import {useContext} from 'react';
import AuthContext from '@/V2/Contexts/AuthContext';

export default function useAuth() {
    return useContext(AuthContext);
}
