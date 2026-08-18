import baseKy from 'ky';
import Cookies from 'js-cookie';

const ky = baseKy.extend({
	headers: {
		Accept: 'application/json',
	},
	hooks: {
		beforeRequest: [
			(state) => {
				if (window.location.host === new URL(state.request.url).host) {
					state.request.headers.set(
						'X-XSRF-TOKEN',
						Cookies.get('XSRF-TOKEN') ?? '',
					);
				}
			},
		],
	},
});

export default ky;
