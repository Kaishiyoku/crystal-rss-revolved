import ky from 'ky';
import NProgress from 'nprogress';
import Cookies from 'js-cookie';
import 'nprogress/nprogress.css';

window.ky = ky.extend({
    headers: {
        Accept: 'application/json',
    },
    hooks: {
        beforeRequest: [
            (request) => {
                NProgress.start();

                if (window.location.host === new URL(request.url).host) {
                    request.headers.set('X-XSRF-TOKEN', Cookies.get('XSRF-TOKEN') ?? '');
                }
            },
        ],
        afterResponse: [
            (request, options, response) => {
                NProgress.done();

                return response;
            },
        ],
        beforeError: [
            (error) => {
                NProgress.done();

                return error;
            },
        ],
    },
});

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

// import Echo from 'laravel-echo';

// import Pusher from 'pusher-js';
// window.Pusher = Pusher;

// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     key: import.meta.env.VITE_PUSHER_APP_KEY,
//     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER ?? 'mt1',
//     wsHost: import.meta.env.VITE_PUSHER_HOST
//         ? import.meta.env.VITE_PUSHER_HOST
//         : `ws-${import.meta.env.VITE_PUSHER_APP_CLUSTER}.pusher.com`,
//     wsPort: import.meta.env.VITE_PUSHER_PORT ?? 80,
//     wssPort: import.meta.env.VITE_PUSHER_PORT ?? 443,
//     forceTLS: (import.meta.env.VITE_PUSHER_SCHEME ?? 'https') === 'https',
//     enabledTransports: ['ws', 'wss'],
// });
