import type { route as routeFn } from "ziggy-js";
import type { KyInstance } from "ky";

declare global {
	// eslint-disable-next-line @typescript-eslint/consistent-type-definitions
	interface Window {
		ky: KyInstance;
		appName: string;
	}

	// eslint-disable-next-line @typescript-eslint/consistent-type-definitions
	interface SymbolConstructor {
		readonly observable: symbol;
	}

	const route: typeof routeFn;
}
