import {AxiosInstance} from 'axios';
import ziggyRoute, {Config as ZiggyConfig} from 'ziggy-js';

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    const route: typeof ziggyRoute;
    const Ziggy: ZiggyConfig;
}
