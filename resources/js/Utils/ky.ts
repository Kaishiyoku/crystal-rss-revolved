import baseKy from 'ky';
import Cookies from 'js-cookie';
import { progress } from '@inertiajs/react';

const ky = baseKy.extend({
	headers: {
		Accept: 'application/json',
	},
	hooks: {
		beforeRequest: [
			(request) => {
				progress.start();

				if (window.location.host === new URL(request.url).host) {
					request.headers.set('X-XSRF-TOKEN', Cookies.get('XSRF-TOKEN') ?? '');
				}
			},
		],
		afterResponse: [
			(_request, _options, response) => {
				progress.finish();

				return response;
			},
		],
		beforeError: [
			(error) => {
				progress.finish();

				return error;
			},
		],
	},
});

export default ky;
