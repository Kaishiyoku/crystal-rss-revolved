import './bootstrap';
import '../css/app.css';

import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {LaravelReactI18nProvider, useLaravelReactI18n} from 'laravel-react-i18n';

const browserLang = navigator.language.substring(0, 2);

window.appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${window.appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({el, App, props}) {
        const root = createRoot(el);

        const AppWithLoadedTranslations = () => {
            const {loading} = useLaravelReactI18n();

            // wait until all translations are loaded
            if (loading) {
                return null;
            }

            return <App {...props}/>;
        };

        root.render(
            <LaravelReactI18nProvider
                locale={browserLang}
                fallbackLocale="en"
                files={import.meta.glob('/lang/*.json', {eager: true})}
            >
                <AppWithLoadedTranslations {...props}/>
            </LaravelReactI18nProvider>
        );
    },
    progress: {color: '#7c3aed'},
});
