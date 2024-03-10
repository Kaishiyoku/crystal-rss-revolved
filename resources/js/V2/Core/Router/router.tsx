import {createBrowserRouter} from 'react-router-dom';
import categoriesLoader from '@/V2/Core/Router/Loaders/categoriesLoader';
import AuthenticatedLayout from '@/V2/Core/AuthenticatedLayout';
import CategoriesIndex from '@/V2/Pages/Categories/CategoriesIndex';
import CategoriesCreate from '@/V2/Pages/Categories/CategoriesCreate';
import RouteHandle from '@/V2/types/RouteHandle';
import CategoriesEdit from '@/V2/Pages/Categories/CategoriesEdit';
import categoryLoader from '@/V2/Core/Router/Loaders/categoryLoader';
import layoutLoader from '@/V2/Core/Router/Loaders/layoutLoader';
import updateCategoryAction from '@/V2/Core/Router/Actions/updateCategoryAction';
import ErrorPage from '@/V2/Core/ErrorPage';
import AuthProvider from '@/V2/Core/AuthProvider';
import createCategoryAction from '@/V2/Core/Router/Actions/createCategoryAction';

const router = createBrowserRouter([
    {
        path: '/app',
        element: <AuthProvider><AuthenticatedLayout/></AuthProvider>,
        errorElement: <ErrorPage/>,
        loader: layoutLoader,
        handle: {titleKey: 'Home'} as RouteHandle,
        children: [
            {
                path: 'categories',
                element: <CategoriesIndex/>,
                loader: categoriesLoader,
                handle: {titleKey: 'Categories'} as RouteHandle,
                children: [
                    {
                        path: 'create',
                        element: <CategoriesCreate/>,
                        action: createCategoryAction,
                        handle: {hide: true, titleKey: 'Add category'} as RouteHandle,
                    },
                    {
                        path: ':categoryId/edit',
                        element: <CategoriesEdit/>,
                        loader: categoryLoader('/edit'),
                        action: updateCategoryAction,
                        handle: {hide: true, titleKey: 'Edit category'} as RouteHandle,
                    },
                ],
            },
        ],
    },
]);

export default router;
