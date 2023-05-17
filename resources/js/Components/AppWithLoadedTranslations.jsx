import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function AppWithLoadedTranslations({app: App, ...props}) {
    const {loading} = useLaravelReactI18n();

    // wait until all translations are loaded
    if (loading) {
        return null;
    }

    return <App {...props}/>;
}
