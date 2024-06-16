import {createBrowserRouter} from 'react-router-dom';
import categoriesLoader from '@/V2/Pages/Categories/Loaders/categoriesLoader';
import AuthenticatedLayout from '@/V2/Core/AuthenticatedLayout';
import CategoriesIndex from '@/V2/Pages/Categories/CategoriesIndex';
import CategoriesCreate from '@/V2/Pages/Categories/CategoriesCreate';
import request from '@/V2/request';
import {HTTPError} from 'ky';
import ValidationErrors from '@/V2/types/ValidationErrors';
import RouteHandle from '@/V2/types/RouteHandle';
import CategoriesEdit from '@/V2/Pages/Categories/CategoriesEdit';
import categoryLoader from '@/V2/Pages/Categories/Loaders/categoryLoader';
import layoutLoader from '@/V2/Pages/Loaders/layoutLoader';

const Error = () => <div>An error occurred.</div>;

const router = createBrowserRouter([
    {
        path: '/v2',
        element: <AuthenticatedLayout/>,
        errorElement: <Error/>,
        loader: layoutLoader,
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
