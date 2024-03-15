import {Outlet, useNavigate} from 'react-router-dom';
import useAuth from '@/V2/Hooks/useAuth';
import {useEffect} from 'react';

export default function Admin() {
    const {user} = useAuth();
    const navigate = useNavigate();

    useEffect(() => {
        if (!user?.is_admin) {
            navigate('/');
        }
    }, [user]);

    return <Outlet/>;
}
