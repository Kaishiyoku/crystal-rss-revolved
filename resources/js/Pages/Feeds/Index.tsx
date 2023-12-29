import {Head, Link} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import LinkStack from '@/Components/LinkStack';
import formatDateTime from '@/Utils/formatDateTime';
import EmptyState from '@/Components/EmptyState';
import NewspaperOutlineIcon from '@/Icons/NewspaperOutlineIcon';
import {PageProps} from '@/types';
import {RouteParams} from 'ziggy-js';
import Feed from '@/types/Models/Feed';
import PhotoSolidIcon from '@/Icons/PhotoSolidIcon';

export default function Index({feeds, ...props}: PageProps & { feeds: Feed[]; }) {
    const {t, tChoice} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Feeds')}</Header>}
        >
            <Head title={t('Feeds')}/>

            <Actions>
                <Link
                    href={route('feeds.create')}
                    className="link-secondary"
                >
                    {t('Add feed')}
                </Link>
            </Actions>

            {feeds.length > 0
                ? (
                    <LinkStack>
                        {feeds.map((feed) => (
                            <LinkStack.Item
                                key={feed.id}
                                href={route('feeds.edit', feed as unknown as RouteParams<'feeds.edit'>)}
                                className="block sm:flex justify-between"
                            >
                                <div className="flex items-center">
                                    {feed.favicon_url
                                        ? (
                                            <img
                                                loading="lazy"
                                                src={feed.favicon_url}
                                                alt={feed.name}
                                                className="w-5 h-5 rounded mr-4"
                                            />
                                        )
                                        : <PhotoSolidIcon className="w-5 h-5 mr-4"/>
                                    }

                                    <div>
                                        <div className="font-semibold">
                                            {feed.name}
                                        </div>

                                        {feed.last_failed_at && (
                                            <div className="text-sm text-pink-500">
                                                {t('feed.last_failed_at', {date: formatDateTime(feed.last_failed_at)})}
                                            </div>
                                        )}

                                        <div className="text-sm text-muted">
                                            {feed.category.name}
                                        </div>
                                    </div>
                                </div>

                                <div className="text-sm sm:text-base text-muted">
                                    {tChoice('feed.feed_items_count', feed.feed_items_count)}
                                </div>
                            </LinkStack.Item>
                        ))}
                    </LinkStack>
                )
                : (
                    <EmptyState
                        icon={NewspaperOutlineIcon}
                        message={t('No feeds.')}
                        description={t('Get started by creating a new feed.')}
                    />
                )}
        </AuthenticatedLayout>
    );
}
