import type ziggyRoute from 'ziggy-js';
import {Config as ZiggyConfig} from 'ziggy-js';
import {KyInstance} from 'ky';

declare global {
    // eslint-disable-next-line @typescript-eslint/consistent-type-definitions
    interface Window {
        ky: KyInstance;
        appName: string;
    }

    const route: typeof ziggyRoute;
    const Ziggy: ZiggyConfig;
}
