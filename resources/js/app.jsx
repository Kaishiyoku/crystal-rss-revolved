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
            const {getActiveLanguage, isLoaded} = useLaravelReactI18n();

            // wait until all translations are loaded
            if (!isLoaded(getActiveLanguage())) {
                return null;
            }

            return <App {...props}/>;
        };

        root.render(
            <LaravelReactI18nProvider
                lang={browserLang}
                fallbackLang="en"
                resolve={async (lang) => {
                    const langs = import.meta.glob('../../lang/*.json')

                    const fn = langs[`../../lang/${lang}.json`];

                    if (typeof fn === 'function') {
                        return await fn();
                    }
                }}
            >
                <AppWithLoadedTranslations {...props}/>
            </LaravelReactI18nProvider>
        );
    },
    progress: {
        color: '#4f46e5',
    },
});
