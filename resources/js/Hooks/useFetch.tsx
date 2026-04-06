import Cookies from 'js-cookie';
import { progress } from '@inertiajs/react';

export default function useFetch() {
	async function fetchFn<ResponseType>(
		url: string,
		method: string,
		body: Record<string, unknown> | null = null,
	) {
		progress.start();

		try {
			const response = await fetch(url, {
				headers: {
					'Content-Type': 'application/json',
					Accept: 'application/json',
					'X-XSRF-TOKEN': Cookies.get('XSRF-TOKEN') ?? '',
				},
				method,
				body: body ? JSON.stringify(body) : '',
			});

			return response.json() as Promise<ResponseType>;
		} catch (error) {
			// biome-ignore lint/complexity/noUselessCatch: we need the catch here so we can finish the progress bar on error
			throw error;
		} finally {
			progress.finish();
		}
	}

	return {
		fetch: fetchFn,
	};
}
