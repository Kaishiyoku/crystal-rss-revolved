import {AxiosInstance} from 'axios';
import ziggyRoute, {Config as ZiggyConfig} from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
        appName: string;
    }

    const route: typeof ziggyRoute;
    const Ziggy: ZiggyConfig;
}
