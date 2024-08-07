import {Head, Link} from '@inertiajs/react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import ApplicationLogo from '@/Components/ApplicationLogo';
import {WelcomeProps} from '@/types';

export default function Welcome(props: WelcomeProps) {
    const {t} = useLaravelReactI18n();

    return (
        <>
            <Head title={t('Welcome')}/>

            <div className="relative sm:flex sm:justify-center sm:items-center sm:pt-12 pb-4 sm:pb-12 min-h-screen bg-gradient-to-br from-blue-500 to-cyan-500">
                <div className="sm:fixed sm:top-0 sm:right-0 p-6 text-right">
                    {props.auth.user
                        ? (
                            <Link href={route('dashboard')} className="link-light">
                                {t('Dashboard')}
                            </Link>
                        )
                        : (
                            <>
                                <Link href={route('login')} className="link-light">
                                    {t('Log in')}
                                </Link>

                                <Link href={route('register')} className="link-light ml-4">
                                    {t('Register')}
                                </Link>
                            </>
                        )}
                </div>

                <div className="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
                    <div className="flex flex-col justify-center items-center pt-8 sm:pt-0">
                        <div className="flex justify-center items-center bg-white/25 p-2 rounded-full w-52 h-52">
                            <ApplicationLogo className="h-full p-5"/>
                        </div>

                        <div className="text-6xl md:text-8xl text-center py-8 text-transparent bg-clip-text bg-gradient-to-r from-blue-300 to-cyan-300">
                            {window.appName}
                        </div>

                        <div className="prose prose-2xl prose-invert max-w-xl">
                            <p>
                                {t(':name is a minimalist newsfeed reader.', {name: window.appName})}
                            </p>

                            <p>
                                {t('The features are limited by intention to be able to concentrate on what matters: reading the news.')}
                            </p>

                            <p>
                                {t('Any RSS feed can be added and article summaries are then being fetched and displayed on the dashboard automatically.')}
                            </p>

                            <div className="flex space-x-4">
                                {props.contactEmail && (
                                    <a href={`mailto:${props.contactEmail}`}>
                                        {t('Contact')}
                                    </a>
                                )}

                                {props.githubUrl && (
                                    <a href={props.githubUrl}>
                                        {t('GitHub')}
                                    </a>
                                )}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </>
    )
    ;
}
