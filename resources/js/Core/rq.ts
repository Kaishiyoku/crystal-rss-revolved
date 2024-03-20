import ky from 'ky';
import NProgress from 'nprogress';
import Cookies from 'js-cookie';

const rq = ky.extend({
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

export default rq;
