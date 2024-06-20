import {ReactNode} from 'react';
import {BasePageProps, PageProps} from '@/types';
import Navigation from '@/Core/Navigation';
import {usePage} from '@inertiajs/react';

export default function Authenticated({auth, header, children}: BasePageProps & { header: ReactNode; children: ReactNode; }) {
    const {selectedFeedId, unreadFeeds} = usePage<PageProps>().props;

    return (
        <Navigation user={auth.user} selectedFeedId={selectedFeedId} unreadFeeds={unreadFeeds}>
            {(header) && (
                <header>
                    {header}
                </header>
            )}

            <main>
                {children}
            </main>
        </Navigation>
    );
}
