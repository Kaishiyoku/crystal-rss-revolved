import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import formatDateTime from '@/Utils/formatDateTime';
import EmptyState from '@/Components/EmptyState';
import {PageProps} from '@/types';
import {RouteParams} from 'ziggy-js';
import FeedWithFeedItemsCount from '@/types/generated/Models/FeedWithFeedItemsCount';
import {Button} from '@/Components/Button';
import {LinkStack, LinkStackItem} from '@/Components/LinkStack';
import {RssIcon} from '@heroicons/react/20/solid';

export default function Index({feeds, ...props}: PageProps & { feeds: FeedWithFeedItemsCount[]; }) {
    const {t, tChoice} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={('Feeds')}
            actions={(
                <Button href={route('feeds.create')}>
                    {t('Add feed')}
                </Button>
            )}
        >
            <Head title={t('Feeds')}/>

            {feeds.length > 0
                ? (
                    <LinkStack>
                        {feeds.map((feed) => (
                            <LinkStackItem
                                key={feed.id}
                                image={feed.favicon_url
                                    ? (
                                        <img
                                            loading="lazy"
                                            src={feed.favicon_url}
                                            alt={feed.name}
                                            className="size-5 rounded-full"
                                        />
                                    )
                                    : <RssIcon className="size-5"/>}
                                title={feed.name}
                                url={route('feeds.edit', feed as unknown as RouteParams<'feeds.edit'>)}
                            >
                                <div className="text-sm text-muted">
                                    <div className="flex space-x-2">

                                        <div>
                                            {feed.category.name}
                                        </div>
                                    </div>

                                    <div className="text-muted">
                                        {tChoice('feed.feed_items_count', feed.feed_items_count)}
                                    </div>

                                    <div className="text-muted">
                                        {feed.is_purgeable
                                            ? tChoice('feed.purge', props.monthsAfterPruningFeedItems)
                                            : t('feed.no_purge')
                                        }
                                    </div>

                                    {feed.last_failed_at && (
                                        <div className="text-pink-500">
                                            {t('feed.last_failed_at', {date: formatDateTime(feed.last_failed_at)})}
                                        </div>
                                    )}
                                </div>
                            </LinkStackItem>
                        ))}
                    </LinkStack>
                )
                : (
                    <EmptyState
                        icon={RssIcon}
                        message={t('No feeds.')}
                        description={t('Get started by creating a new feed.')}
                    />
                )}
        </AuthenticatedLayout>
    );
}
