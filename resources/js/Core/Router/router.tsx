import {createBrowserRouter, redirect} from 'react-router-dom';
import categoriesLoader from '@/Core/Router/Loaders/categoriesLoader';
import AuthenticatedLayout from '@/Core/AuthenticatedLayout';
import CategoriesIndexPage from '@/Pages/Categories/CategoriesIndexPage';
import CreateCategoryPage from '@/Pages/Categories/CreateCategoryPage';
import RouteHandle from '@/types/RouteHandle';
import EditCategoryPage from '@/Pages/Categories/EditCategoryPage';
import editCategoryLoader from '@/Core/Router/Loaders/editCategoryLoader';
import layoutLoader from '@/Core/Router/Loaders/layoutLoader';
import editCategoryAction from '@/Core/Router/Actions/editCategoryAction';
import ErrorPage from '@/Core/ErrorPage';
import AuthProvider from '@/Core/AuthProvider';
import createCategoryAction from '@/Core/Router/Actions/createCategoryAction';
import FeedsIndexPage from '@/Pages/Feeds/FeedsIndexPage';
import feedsLoader from '@/Core/Router/Loaders/feedsLoader';
import CreateFeedPage from '@/Pages/Feeds/CreateFeedPage';
import createFeedLoader from '@/Core/Router/Loaders/createFeedLoader';
import createFeedAction from '@/Core/Router/Actions/createFeedAction';
import EditFeedPage from '@/Pages/Feeds/EditFeedPage';
import editFeedLoader from '@/Core/Router/Loaders/editFeedLoader';
import editFeedAction from '@/Core/Router/Actions/editFeedAction';
import Home from '@/Pages/Home';
import feedItemsLoader from '@/Core/Router/Loaders/feedItemsLoader';
import TotalNumberOfFeedItemsProvider from '@/Core/TotalNumberOfFeedItemsProvider';
import Admin from '@/Pages/Admin/Admin';
import usersLoader from '@/Core/Router/Loaders/usersLoader';
import UsersIndexPage from '@/Pages/Admin/Users/UsersIndexPage';
import usersAction from '@/Core/Router/Actions/usersAction';
import ProfilePage from '@/Pages/ProfilePage';
import profileLoader from '@/Core/Router/Loaders/profileLoader';
import profileAction from '@/Core/Router/Actions/profileAction';
import NotFoundPage from '@/Core/NotFoundPage';

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
    {
        path: '*',
        element: <NotFoundPage/>,
    },
]);

export default router;
