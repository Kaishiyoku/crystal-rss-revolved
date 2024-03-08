import {createBrowserRouter} from 'react-router-dom';
import categoriesLoader from '@/React/Pages/Categories/Loaders/categoriesLoader';
import AuthenticatedLayout from '@/React/Core/AuthenticatedLayout';
import CategoriesIndex from '@/React/Pages/Categories/CategoriesIndex';

const router = createBrowserRouter([
    {
        path: '/react/',
        element: <AuthenticatedLayout/>,
        handle: {
            title: 'Home',
            headline: 'Home',
        },
        children: [
            {
                path: 'categories',
                element: <CategoriesIndex/>,
                loader: categoriesLoader,
                handle: {
                    title: 'Categories',
                    headline: 'Categories',
                },
            },
        ],
    },
]);

export default router;
