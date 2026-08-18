import type { route as routeFn } from 'ziggy-js';
import type { KyInstance } from 'ky';

declare global {
	interface Window {
		ky: KyInstance;
		appName: string;
	}

	interface SymbolConstructor {
		readonly observable: symbol;
	}

	const route: typeof routeFn;
}
