import ApplicationLogo from '@/Components/ApplicationLogo';
import {Link} from '@inertiajs/react';
import type {PropsWithChildren} from 'react';
import clsx from 'clsx';
import {AuthLayout} from '@/Components/AuthLayout';

export default function Guest({minimal = false, children}: { minimal?: boolean } & PropsWithChildren) {
    return (
        <div
            className={clsx({
                'lg:bg-zinc-100 lg:dark:bg-zinc-900': minimal,
                'bg-zinc-100 dark:bg-zinc-900 min-h-screen flex flex-col sm:justify-center items-center p-6': !minimal
            })}
        >
            {minimal
                ? (
                    <AuthLayout>
                        {children}
                    </AuthLayout>
                )
                : (
                    <>
                        <div>
                            <Link href="/">
                                <ApplicationLogo className="w-20 h-20 fill-current text-zinc-500"/>
                            </Link>
                        </div>

                        <div
                            className="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-zinc-800 shadow-md overflow-hidden rounded-lg">
                            {children}
                        </div>
                    </>
                )
            }
        </div>
    );
}
