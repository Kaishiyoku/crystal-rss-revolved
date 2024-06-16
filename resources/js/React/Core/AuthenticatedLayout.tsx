import {Outlet} from 'react-router-dom';
import Breadcrumbs from '@/React/Core/Breadcrumbs';

const AuthenticatedLayout = () => {
    return (
        <>
            <header className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8 text-xl flex space-x-2">
                <Breadcrumbs/>
            </header>

            <main className="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                <Outlet/>
            </main>
        </>
    );
};

export default AuthenticatedLayout;
