import './bootstrap';
import '../css/app.css';

import {createRoot} from 'react-dom/client';
import {createInertiaApp} from '@inertiajs/react';
import {resolvePageComponent} from 'laravel-vite-plugin/inertia-helpers';
import {LaravelReactI18nProvider} from 'laravel-react-i18n';
import getBrowserLocale from '@/Utils/getBrowserLocale';
import HydratedApp from '@/Components/HydratedApp';
import {Provider} from 'jotai';
import {unreadFeedsAtom} from '@/Stores/unreadFeedsAtom';
import {PageProps} from '@/types';
import AtomsHydrator from '@/Core/AtomsHydrator';

window.appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

void createInertiaApp({
    title: (title: string): string => `${title} - ${window.appName}`,
    resolve: (name: string) => resolvePageComponent(`./Pages/${name}.tsx`, import.meta.glob('./Pages/**/*.tsx')),
    setup({el, App, props}) {
        createRoot(el).render(
            <Provider>
                <AtomsHydrator atomValues={[[unreadFeedsAtom, (props.initialPage.props as PageProps).unreadFeeds]]}>
                    <LaravelReactI18nProvider
                        locale={getBrowserLocale()}
                        fallbackLocale="en"
                        files={import.meta.glob('/lang/*.json')}
                    >
                        <HydratedApp app={App} {...props}/>
                    </LaravelReactI18nProvider>
                </AtomsHydrator>
            </Provider>
        );
    },
    progress: {
        color: '#2563eb',
        showSpinner: false,
    },
});
