import {Head} from '@inertiajs/react';
import {PageProps} from '@/types';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import ApplicationLogo from '@/Components/ApplicationLogo';
import {Link} from '@/Components/Link';

export default function Welcome({auth, canLogin, canRegister}: PageProps<{ canLogin: boolean; canRegister: boolean; }>) {
    const {t} = useLaravelReactI18n();

    return (
        <>
            <Head title={t('Welcome')}/>

            <svg
                xmlns="http://www.w3.org/2000/svg"
                version="1.1"
                viewBox="0 0 640 1200"
                className="sm:hidden top-0 absolute z-10 w-full opacity-75 dark:opacity-50"
            >
                <defs>
                    <filter
                        id="bbblurry-filter"
                        x="-100%"
                        y="-100%"
                        width="400%"
                        height="400%"
                        filterUnits="objectBoundingBox"
                        primitiveUnits="userSpaceOnUse"
                        colorInterpolationFilters="sRGB"
                    >
                        <feGaussianBlur
                            stdDeviation="100"
                            x="0%"
                            y="0%"
                            width="100%"
                            height="100%"
                            in="SourceGraphic"
                            edgeMode="none"
                            result="blur"
                        />
                    </filter>
                </defs>
                <g filter="url(#bbblurry-filter)">
                    <ellipse className="fill-blue-600" rx="200" ry="200" cx="180" cy="650"/>
                    <ellipse className="fill-violet-600" rx="200" ry="200" cx="175" cy="150"/>
                    <ellipse className="fill-teal-600" rx="200" ry="200" cx="350" cy="400"/>
                </g>
            </svg>

            <svg
                xmlns="http://www.w3.org/2000/svg"
                version="1.1"
                viewBox="0 0 800 450"
                className="hidden sm:block top-0 z-10 absolute size-full opacity-80 dark:opacity-50"
            >
                <defs>
                    <filter
                        id="bbblurry-filter"
                        x="-100%"
                        y="-100%"
                        width="400%"
                        height="400%"
                        filterUnits="objectBoundingBox"
                        primitiveUnits="userSpaceOnUse"
                        colorInterpolationFilters="sRGB"
                    >
                        <feGaussianBlur
                            stdDeviation="65"
                            x="0%"
                            y="0%"
                            width="100%"
                            height="100%"
                            in="SourceGraphic"
                            edgeMode="none"
                            result="blur"
                        />
                    </filter>
                </defs>
                <g filter="url(#bbblurry-filter)">
                    <ellipse className="fill-blue-600" rx="112" ry="113" cx="120" cy="175"/>
                    <ellipse className="fill-violet-600" rx="112" ry="113" cx="280" cy="115"/>
                    <ellipse className="fill-teal-600" rx="112" ry="113" cx="725" cy="75"/>
                </g>
            </svg>

            <div className="text-black/50 dark:text-white/50">
                <div className="relative min-h-screen flex flex-col items-center selection:bg-blue-500 selection:text-white">
                    <div className="relative w-full max-w-2xl p-6 lg:max-w-7xl">
                        <header className="flex justify-end">
                            <nav className="flex space-x-4 z-20">
                                {auth.user
                                    ? (
                                        <Link href={route('dashboard')} color="zinc">
                                            {t('Dashboard')}
                                        </Link>
                                    )
                                    : (
                                        <>
                                            {canLogin && (
                                                <Link href={route('login')} color="zinc">
                                                    {t('Log in')}
                                                </Link>
                                            )}

                                            {canRegister && (
                                                <Link href={route('register')} color="zinc">
                                                    {t('Register')}
                                                </Link>
                                            )}
                                        </>
                                    )}
                            </nav>
                        </header>
                    </div>

                    <div className="relative flex flex-col items-center w-full max-w-2xl px-6 py-20 lg:max-w-7xl">
                        <div className="absolute w-full h-full welcome-pattern top-0"/>
                        <div className="absolute w-full h-full welcome-pattern-gradient dark:welcome-pattern-gradient-dark top-0"/>

                        <div className="z-20 pb-12">
                            <ApplicationLogo className="size-32"/>
                        </div>

                        <div className="z-20 text-center text-8xl font-light bg-gradient-to-r from-blue-500 via-violet-500 to-teal-500 dark:from-blue-600 dark:via-violet-600 dark:to-teal-600 bg-clip-text">
                            {window.appName}
                        </div>
                    </div>
                </div>
            </div>
        </>
    );
}
