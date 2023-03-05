import './bootstrap';
import '../css/app.css';

import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {LaravelReactI18nProvider} from 'laravel-react-i18n';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.jsx`, import.meta.glob('./Pages/**/*.jsx')),
    setup({el, App, props}) {
        const root = createRoot(el);

        root.render(
            <LaravelReactI18nProvider
                lang="de"
                fallbackLang="en"
                resolve={async (lang) => {
                    const langs = import.meta.glob('../../lang/*.json')

                    const fn = langs[`../../lang/${lang}.json`];

                    if (typeof fn === 'function') {
                        return await fn();
                    }
                }}
            >
                <App {...props}/>
            </LaravelReactI18nProvider>
        );
    },
    progress: {
        color: '#4f46e5',
    },
});
