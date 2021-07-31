import onDomReady from './utils/onDomReady';

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

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: process.env.MIX_PUSHER_APP_KEY,
    cluster: process.env.MIX_PUSHER_APP_CLUSTER,
    forceTLS: process.env.MIX_WEBSOCKETS_USE_TLS === 'true',
    wsHost: window.location.hostname,
    wsPort: parseInt(process.env.MIX_LARAVEL_WEBSOCKETS_PORT, 10),
    wssPort: parseInt(process.env.MIX_LARAVEL_WEBSOCKETS_PORT, 10),
});

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
});
