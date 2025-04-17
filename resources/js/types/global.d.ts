import {PageProps as InertiaPageProps} from '@inertiajs/core';
import {route as routeFn} from 'ziggy-js';
import {KyInstance} from 'ky';
import {PageProps as AppPageProps} from './';

declare global {
    // eslint-disable-next-line @typescript-eslint/consistent-type-definitions
    interface Window {
        ky: KyInstance;
        appName: string;
    }

    // eslint-disable-next-line @typescript-eslint/consistent-type-definitions
    interface SymbolConstructor {
        readonly observable: symbol;
    }

    const route: typeof routeFn;
}

declare module '@inertiajs/core' {
    type PageProps = {} & InertiaPageProps & AppPageProps;
}
