import {ReactNode} from 'react';
import {BasePageProps, PageProps} from '@/types';
import Navigation from '@/Core/Navigation';
import {usePage} from '@inertiajs/react';
import {Heading} from '@/Components/Heading';

export default function Authenticated({auth, header, actions, children}: BasePageProps & { header: ReactNode; actions?: ReactNode; children: ReactNode; }) {
    const {selectedFeedId, unreadFeeds} = usePage<PageProps>().props;

    return (
        <Navigation user={auth.user} selectedFeedId={selectedFeedId} unreadFeeds={unreadFeeds}>
            {header && (
                <div className="flex w-full flex-wrap items-end justify-between gap-4 border-b border-zinc-950/10 mb-8 pb-6 dark:border-white/10">
                    <Heading>{header}</Heading>

                    {actions && (
                        <div className="flex gap-4">
                            {actions}
                        </div>
                    )}
                </div>
            )}

            <main>
                {children}
            </main>
        </Navigation>
    );
}
