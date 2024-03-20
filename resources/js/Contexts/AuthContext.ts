import {createContext} from 'react';
import AuthContextType from '@/types/AuthContextType';
import noop from '@/Utils/noop';

const AuthContext = createContext<AuthContextType>({user: null, setUser: noop});

export default AuthContext;
