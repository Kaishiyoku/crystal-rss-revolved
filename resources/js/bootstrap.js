import onDomReady from './utils/onDomReady';
import Push from 'push.js';
import {scrollTo} from 'scroll-js';

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = require('axios');

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from 'laravel-echo';

window.Pusher = require('pusher-js');

if (process.env.MIX_ENABLE_PUSH_NOTIFICATIONS === 'true') {
    window.Echo = new Echo({
        broadcaster: 'pusher',
        key: process.env.MIX_PUSHER_APP_KEY,
        cluster: process.env.MIX_PUSHER_APP_CLUSTER,
        forceTLS: process.env.MIX_WEBSOCKETS_USE_TLS === 'true',
        wsHost: window.location.hostname,
        wsPort: 6001,
        wssPort: 6001,
    });
}

window.sendPushNotification = (title, body, timeout = 4000, callback = () => {}) => {
    Push.create(title, {
        body,
        icon: '/img/favicon/apple-touch-icon.png',
        timeout,
        onClick: function () {
            window.focus();
            this.close();

            callback();
        }
    });
};

window.hasPushNotificationsEnabled = () => Push.Permission.has();
window.requestPushNotificationPermission = (onGranted = () => {}, onDenied = () => {}) => Push.Permission.request(onGranted, onDenied);
window.getNativePushNotificationPermissionLevel = () => Push.Permission.get();

onDomReady(() => {
    document.querySelectorAll('[data-confirm]').forEach((element) => {
        element.addEventListener('click', (event) => {
            const confirmationText = element.getAttribute('data-confirm');

            if (!confirm(confirmationText)) {
                event.preventDefault();

                return false;
            }
        });
    });

    document.querySelectorAll('[data-scroll-to-top]').forEach((element) => {
        element.addEventListener('click', () => {
            scrollTo(document.body, {top: 0, duration: 750, easing: 'ease-in-out'});
        });
    });
});
