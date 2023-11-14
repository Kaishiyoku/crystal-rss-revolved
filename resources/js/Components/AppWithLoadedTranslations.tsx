import {useLaravelReactI18n} from 'laravel-react-i18n';

// @ts-expect-error the app type doesn't matter here because we directly use this component in our Inertia setup function
export default function AppWithLoadedTranslations({app: App, ...props}) {
    const {loading} = useLaravelReactI18n();

    // wait until all translations are loaded
    if (loading) {
        return null;
    }

    return <App {...props}/>;
}
