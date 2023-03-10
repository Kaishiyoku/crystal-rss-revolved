import {Head, Link} from '@inertiajs/react';
import ApplicationLogo from '@/Components/ApplicationLogo';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function Welcome(props) {
    const {t} = useLaravelReactI18n();

    return (
        <>
            <Head title="Welcome" />
            <div className="relative sm:flex sm:justify-center sm:items-center min-h-screen bg-gradient-to-br from-indigo-500 to-purple-500">
                <div className="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                    {props.auth.user ? (
                        <Link
                            href={route('dashboard')}
                            className="link-light"
                        >
                            {t('Dashboard')}
                        </Link>
                    ) : (
                        <>
                            <Link
                                href={route('login')}
                                className="link-light"
                            >
                                {t('Log in')}
                            </Link>

                            <Link
                                href={route('register')}
                                className="link-light ml-4"
                            >
                                {t('Register')}
                            </Link>
                        </>
                    )}
                </div>

                <div className="max-w-6xl mx-auto sm:px-6 lg:px-8">
                    <div className="flex flex-col justify-center items-center pt-8 sm:pt-0">
                        <div className="flex justify-center items-center bg-white/25 p-2 rounded-full w-40 h-40">
                            <ApplicationLogo className="h-full p-5"/>
                        </div>

                        <div className="text-6xl md:text-8xl text-center pt-8 text-transparent bg-clip-text bg-gradient-to-r from-indigo-300 to-purple-300">
                            {window.appName}
                        </div>

                        <div className="max-w-xl mx-auto mt-16 px-4 sm:px-0 py-4 text-gray-200">
                            <p className="text-2xl pb-8">
                                {t('welcome.headline', {name: window.appName})}
                            </p>

                            <p className="text-xl pb-4">
                                {t('welcome.text_1')}
                            </p>

                            <p className="text-xl pb-4">
                                {t('welcome.text_2')}
                            </p>

                            <p className="text-xl">
                                {t('welcome.text_3')}
                            </p>

                            <div className="mt-16 space-x-4 text-xl">
                                {props.contactEmail && (
                                    <a href={`mailto:${props.contactEmail}`} className="link-light">
                                        {t('Contact')}
                                    </a>
                                )}

                                {props.githubUrl && (
                                    <a href={props.githubUrl} className="link-light">
                                        {t('GitHub')}
                                    </a>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
