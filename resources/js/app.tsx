import './bootstrap';
import '../css/app.css';

import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {LaravelReactI18nProvider} from 'laravel-react-i18n';
import getBrowserLocale from '@/Utils/getBrowserLocale';
import AppWithLoadedTranslations from '@/Components/AppWithLoadedTranslations';

window.appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

void createInertiaApp({
    title: (title: string): string => `${title} - ${window.appName}`,
    resolve: (name: string) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({el, App, props}) {
        createRoot(el).render(
            <LaravelReactI18nProvider
                locale={getBrowserLocale()}
                fallbackLocale="en"
                files={import.meta.glob('/lang/*.json')}
            >
                <AppWithLoadedTranslations app={App} {...props}/>
            </LaravelReactI18nProvider>
        );
    },
    progress: {
        color: '#2563eb',
        showSpinner: false,
    },
});
