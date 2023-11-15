import ziggyRoute, {Config as ZiggyConfig} from 'ziggy-js';
import {KyInstance} from 'ky';

declare global {
    interface Window {
        ky: KyInstance;
        appName: string;
    }

    const route: typeof ziggyRoute;
    const Ziggy: ZiggyConfig;
}
