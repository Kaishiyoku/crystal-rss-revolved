import { useLaravelReactI18n } from "laravel-react-i18n";
import { router } from "@inertiajs/react";
import type { PageProps } from "@/types";
import { useSetAtom } from "jotai/index";
import { unreadFeedsAtom } from "@/Stores/unreadFeedsAtom";

// @ts-expect-error the app type doesn't matter here because we directly use this component in our Inertia setup function
export default function HydratedApp({ app: App, ...props }) {
	const { loading } = useLaravelReactI18n();

	const setUnreadFeedsAtom = useSetAtom(unreadFeedsAtom);

	// wait until all translations are loaded
	if (loading) {
		return null;
	}

	router.on("success", (event) => {
		setUnreadFeedsAtom((event.detail.page.props as PageProps).unreadFeeds);
	});

	return <App {...props} />;
}
