import {createRoot} from 'react-dom/client';
import {RouterProvider} from 'react-router-dom';
import router from '@/React/Core/router';
import NProgress from 'nprogress';

NProgress.configure({
    showSpinner: false,
});

const App = () => {
    return (
        <RouterProvider router={router}/>
    );
};

createRoot(document.getElementById('app')!)
    .render(<App/>);
