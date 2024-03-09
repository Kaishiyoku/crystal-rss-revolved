import {createBrowserRouter} from 'react-router-dom';
import categoriesLoader from '@/React/Pages/Categories/Loaders/categoriesLoader';
import AuthenticatedLayout from '@/React/Core/AuthenticatedLayout';
import CategoriesIndex from '@/React/Pages/Categories/CategoriesIndex';
import CategoriesCreate from '@/React/Pages/Categories/CategoriesCreate';
import request from '@/React/request';
import {HTTPError} from 'ky';
import ValidationErrors from '@/React/types/ValidationErrors';
import RouteHandle from '@/React/types/RouteHandle';
import CategoriesEdit from '@/React/Pages/Categories/CategoriesEdit';
import categoryLoader from '@/React/Pages/Categories/Loaders/categoryLoader';

const Error = () => <div>An error occurred.</div>;

const router = createBrowserRouter([
    {
        path: '/react',
        element: <AuthenticatedLayout/>,
        errorElement: <Error/>,
        handle: {
            title: 'Home',
            headline: 'Home',
        } as RouteHandle,
        children: [
            {
                path: 'categories',
                element: <CategoriesIndex/>,
                loader: categoriesLoader,
                handle: {
                    title: 'Categories',
                    headline: 'Categories',
                } as RouteHandle,
                children: [
                    {
                        path: 'create',
                        element: <CategoriesCreate/>,
                        action: async ({request: req}) => {
                            const formData = await req.formData();

                            try {
                                await request.post('/api/categories', {json: Object.fromEntries(formData)});
                            } catch (exception) {
                                const errorResponse = (exception as HTTPError).response;

                                if (errorResponse.status !== 422) {
                                    throw exception;
                                }

                                return (await errorResponse.json() as { errors: ValidationErrors; }).errors;
                            }

                            return null;
                        },
                        handle: {
                            hide: true,
                            title: 'Add category',
                            headline: 'Add category',
                        } as RouteHandle,
                    },
                    {
                        path: ':categoryId/edit',
                        element: <CategoriesEdit/>,
                        loader: categoryLoader('/edit'),
                        action: async ({params, request: req}) => {
                            const formData = await req.formData();

                            try {
                                await request.put(`/api/categories/${params.categoryId}`, {json: Object.fromEntries(formData)});
                            } catch (exception) {
                                const errorResponse = (exception as HTTPError).response;

                                if (errorResponse.status !== 422) {
                                    throw exception;
                                }

                                return (await errorResponse.json() as { errors: ValidationErrors; }).errors;
                            }

                            return null;
                        },
                        handle: {
                            hide: true,
                            title: 'Edit category',
                            headline: 'Edit category',
                        } as RouteHandle,
                    },
                ],
            },
        ],
    },
]);

export default router;
