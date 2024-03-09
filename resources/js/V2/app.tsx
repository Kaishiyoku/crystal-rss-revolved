import {createRoot} from 'react-dom/client';
import {RouterProvider} from 'react-router-dom';
import router from '@/V2/Core/router';
import NProgress from 'nprogress';
import {LaravelReactI18nProvider} from 'laravel-react-i18n';
import getBrowserLocale from '@/Utils/getBrowserLocale';
import AppWithLoadedTranslations from '@/Components/AppWithLoadedTranslations';

NProgress.configure({
    showSpinner: false,
});

const App = () => {
    return (
        <LaravelReactI18nProvider
            locale={getBrowserLocale()}
            fallbackLocale="en"
            files={import.meta.glob('/lang/*.json', {eager: true})}
        >
            <AppWithLoadedTranslations app={RouterProvider} router={router}/>
        </LaravelReactI18nProvider>
    );
};

createRoot(document.getElementById('app')!)
    .render(<App/>);
