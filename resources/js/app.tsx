import '../bootstrap';
import '../../css/app.css';
import {createRoot} from 'react-dom/client';
import NProgress from 'nprogress';
import {LaravelReactI18nProvider} from 'laravel-react-i18n';
import getBrowserLocale from '@/Utils/getBrowserLocale';
import AppWithLoadedTranslations from '@/Components/AppWithLoadedTranslations';
import AppWithToasts from '@/AppWithToasts';

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
            <AppWithLoadedTranslations app={AppWithToasts}/>
        </LaravelReactI18nProvider>
    );
};

createRoot(document.getElementById('app')!)
    .render(<App/>);
