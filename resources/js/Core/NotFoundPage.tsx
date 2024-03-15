import {Link} from 'react-router-dom';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import ArrowUturnLeftSolidIcon from '@/Icons/ArrowUturnLeftSolidIcon';

export default function NotFoundPage() {
    const {t} = useLaravelReactI18n();

    return (
        <main className="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8 py-12">
            <div className="text-2xl pb-4">
                {t('404—The requested site could not be found.')}
            </div>

            <Link to="/" className="link-secondary inline-flex space-x-2">
                <ArrowUturnLeftSolidIcon className="size-5"/>
                <span>{t('Back to landing page')}</span>
            </Link>
        </main>
    );
}
