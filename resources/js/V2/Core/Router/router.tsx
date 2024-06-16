import {createBrowserRouter, redirect} from 'react-router-dom';
import categoriesLoader from '@/V2/Core/Router/Loaders/categoriesLoader';
import AuthenticatedLayout from '@/V2/Core/AuthenticatedLayout';
import CategoriesIndexPage from '@/V2/Pages/Categories/CategoriesIndexPage';
import CreateCategoryPage from '@/V2/Pages/Categories/CreateCategoryPage';
import RouteHandle from '@/V2/types/RouteHandle';
import EditCategoryPage from '@/V2/Pages/Categories/EditCategoryPage';
import editCategoryLoader from '@/V2/Core/Router/Loaders/editCategoryLoader';
import layoutLoader from '@/V2/Core/Router/Loaders/layoutLoader';
import editCategoryAction from '@/V2/Core/Router/Actions/editCategoryAction';
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
import editFeedAction from '@/V2/Core/Router/Actions/editFeedAction';
import Home from '@/V2/Pages/Home';
import feedItemsLoader from '@/V2/Core/Router/Loaders/feedItemsLoader';
import TotalNumberOfFeedItemsProvider from '@/V2/Core/TotalNumberOfFeedItemsProvider';
import Admin from '@/V2/Pages/Admin/Admin';
import usersLoader from '@/V2/Core/Router/Loaders/usersLoader';
import UsersIndexPage from '@/V2/Pages/Admin/Users/UsersIndexPage';
import usersAction from '@/V2/Core/Router/Actions/usersAction';
import ProfilePage from '@/V2/Pages/ProfilePage';
import profileLoader from '@/V2/Core/Router/Loaders/profileLoader';
import profileAction from '@/V2/Core/Router/Actions/profileAction';

const router = createBrowserRouter([
    {
        path: '/',
        element: <AuthProvider><AuthenticatedLayout/></AuthProvider>,
        errorElement: <ErrorPage/>,
        loader: layoutLoader,
        handle: {titleKey: 'Home'} as RouteHandle,
        children: [
            {
                path: '/',
                element: <TotalNumberOfFeedItemsProvider><Home/></TotalNumberOfFeedItemsProvider>,
                loader: feedItemsLoader,
            },
            {
                path: 'login',
                loader: () => redirect('/'),
            },
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
                        action: editCategoryAction,
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
                        action: editFeedAction,
                        handle: {hide: true, titleKey: 'Edit feed'} as RouteHandle,
                    },
                ],
            },
            {
                path: 'profile',
                element: <ProfilePage/>,
                loader: profileLoader,
                action: profileAction,
                handle: {titleKey: 'Profile'},
            },
            {
                path: 'admin',
                element: <Admin/>,
                children: [
                    {
                        path: 'users',
                        element: <UsersIndexPage/>,
                        loader: usersLoader,
                        action: usersAction,
                        handle: {titleKey: 'Users'} as RouteHandle,
                    },
                ],
            },
        ],
    },
]);

export default router;
