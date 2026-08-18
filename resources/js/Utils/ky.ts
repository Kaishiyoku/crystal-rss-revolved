import baseKy from 'ky';
import Cookies from 'js-cookie';
import { progress } from '@inertiajs/react';

const ky = baseKy.extend({
	headers: {
		Accept: 'application/json',
	},
	hooks: {
		beforeRequest: [
			(state) => {
				progress.start();
				progress.reveal();

				if (window.location.host === new URL(state.request.url).host) {
					state.request.headers.set('X-XSRF-TOKEN', Cookies.get('XSRF-TOKEN') ?? '');
				}
			},
		],
		afterResponse: [
			(state) => {
				progress.finish();

				return state.response;
			},
		],
		beforeError: [
			(state) => {
				progress.finish();

				return state.error;
			},
		],
	},
});

export default ky;
