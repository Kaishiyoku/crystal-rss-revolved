import {createBrowserRouter} from 'react-router-dom';
import categoriesLoader from '@/V2/Core/Router/Loaders/categoriesLoader';
import AuthenticatedLayout from '@/V2/Core/AuthenticatedLayout';
import CategoriesIndexPage from '@/V2/Pages/Categories/CategoriesIndexPage';
import CreateCategoryPage from '@/V2/Pages/Categories/CreateCategoryPage';
import RouteHandle from '@/V2/types/RouteHandle';
import EditCategoryPage from '@/V2/Pages/Categories/EditCategoryPage';
import editCategoryLoader from '@/V2/Core/Router/Loaders/editCategoryLoader';
import layoutLoader from '@/V2/Core/Router/Loaders/layoutLoader';
import updateCategoryAction from '@/V2/Core/Router/Actions/updateCategoryAction';
import ErrorPage from '@/V2/Core/ErrorPage';
import AuthProvider from '@/V2/Core/AuthProvider';
import createCategoryAction from '@/V2/Core/Router/Actions/createCategoryAction';
import FeedsIndexPage from '@/V2/Pages/Feeds/FeedsIndexPage';
import feedsLoader from '@/V2/Core/Router/Loaders/feedsLoader';
import CreateFeedPage from '@/V2/Pages/Feeds/CreateFeedPage';
import createFeedLoader from '@/V2/Core/Router/Loaders/createFeedLoader';
import createFeedAction from '@/V2/Core/Router/Actions/createFeedAction';
import EditFeedPage from '@/V2/Pages/Feeds/EditFeedPage';
import editFeedLoader from '@/V2/Core/Router/Loaders/editFeedLoader';
import updateFeedAction from '@/V2/Core/Router/Actions/updateFeedAction';

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
                element: <CategoriesIndexPage/>,
                loader: categoriesLoader,
                handle: {titleKey: 'Categories'} as RouteHandle,
                children: [
                    {
                        path: 'create',
                        element: <CreateCategoryPage/>,
                        action: createCategoryAction,
                        handle: {hide: true, titleKey: 'Add category'} as RouteHandle,
                    },
                    {
                        path: ':categoryId/edit',
                        element: <EditCategoryPage/>,
                        loader: editCategoryLoader,
                        action: updateCategoryAction,
                        handle: {hide: true, titleKey: 'Edit category'} as RouteHandle,
                    },
                ],
            },
            {
                path: 'feeds',
                element: <FeedsIndexPage/>,
                loader: feedsLoader,
                handle: {titleKey: 'Feeds'} as RouteHandle,
                children: [
                    {
                        path: 'create',
                        element: <CreateFeedPage/>,
                        loader: createFeedLoader,
                        action: createFeedAction,
                        handle: {hide: true, titleKey: 'Add feed'} as RouteHandle,
                    },
                    {
                        path: ':feedId/edit',
                        element: <EditFeedPage/>,
                        loader: editFeedLoader,
                        action: updateFeedAction,
                        handle: {hide: true, titleKey: 'Edit feed'} as RouteHandle,
                    },
                ],
            },
        ],
    },
]);

export default router;
