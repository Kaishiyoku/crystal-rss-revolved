import {ReactNode} from 'react';
import {BasePageProps, PageProps} from '@/types';
import Navigation from '@/Core/Navigation';
import {usePage} from '@inertiajs/react';

export default function Authenticated({auth, header, children}: BasePageProps & { header: ReactNode; children: ReactNode; }) {
    const {selectedFeedId, unreadFeeds} = usePage<PageProps>().props;

    return (
        <Navigation user={auth.user} selectedFeedId={selectedFeedId} unreadFeeds={unreadFeeds}>
            <div className="min-h-screen text-gray-800 dark:text-gray-300 bg-gray-100 dark:bg-gray-900">
                {(header) && (
                    <header className="text-xl max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {header}
                    </header>
                )}

                <main className="max-w-7xl mx-auto pb-12 px-4 sm:px-6 lg:px-8">
                    {children}
                </main>
            </div>
        </Navigation>
    );
}
