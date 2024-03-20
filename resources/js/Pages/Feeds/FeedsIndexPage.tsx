import {Link, Outlet, useLoaderData} from 'react-router-dom';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Actions from '@/Components/Actions';
import LinkStack from '@/Components/LinkStack';
import {isEmpty} from 'ramda';
import EmptyState from '@/Components/EmptyState';
import FeedsLoaderType from '@/types/FeedsLoaderType';
import NewspaperSolidIcon from '@/Icons/NewspaperSolidIcon';
import PhotoSolidIcon from '@/Icons/PhotoSolidIcon';
import formatDateTime from '@/Utils/formatDateTime';
import useAuth from '@/Hooks/useAuth';

export default function FeedsIndexPage() {
    const {user} = useAuth();
    const {feeds, canCreate} = useLoaderData() as FeedsLoaderType;
    const {t, tChoice} = useLaravelReactI18n();

    return (
        <div>
            <Actions>
                {canCreate && (
                    <Link to="/feeds/create" className="link-secondary">
                        {t('Add feed')}
                    </Link>
                )}
            </Actions>

            {isEmpty(feeds)
                ? (
                    <EmptyState
                        icon={NewspaperSolidIcon}
                        message={t('No feeds.')}
                        description={t('Get started by creating a new feed.')}
                    />
                )
                : (
                    <LinkStack>
                        {feeds.map((feed) => (
                            <LinkStack.Item
                                key={feed.id}
                                to={`/feeds/${feed.id}/edit`}
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

                                        <div className="text-sm text-muted">
                                            {feed.category.name}
                                        </div>

                                        <div className="sm:hidden text-sm">
                                            <div className="text-muted">
                                                {tChoice('feed.feed_items_count', feed.feed_items_count)}
                                            </div>

                                            <div className="text-muted">
                                                {feed.is_purgeable
                                                    ? tChoice('feed.purge', user!.months_after_pruning_feed_items)
                                                    : t('feed.no_purge')
                                                }
                                            </div>

                                            {feed.last_failed_at && (
                                                <div className="text-pink-500">
                                                    {t('feed.last_failed_at', {date: formatDateTime(feed.last_failed_at)})}
                                                </div>
                                            )}
                                        </div>
                                    </div>
                                </div>

                                <div className="hidden sm:block text-sm text-right">
                                    <div className="text-muted">
                                        {tChoice('feed.feed_items_count', feed.feed_items_count)}
                                    </div>

                                    <div className="text-muted">
                                        {feed.is_purgeable
                                            ? tChoice('feed.purge', user!.months_after_pruning_feed_items)
                                            : t('feed.no_purge')
                                        }
                                    </div>

                                    {feed.last_failed_at && (
                                        <div className="text-pink-500">
                                            {t('feed.last_failed_at', {date: formatDateTime(feed.last_failed_at)})}
                                        </div>
                                    )}
                                </div>
                            </LinkStack.Item>
                        ))}
                    </LinkStack>
                )}

            <Outlet/>
        </div>
    );
}
