import {Head, Link} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import LinkStack from '@/Components/LinkStack';
import formatDateTime from '@/Utils/formatDateTime';
import EmptyState from '@/Components/EmptyState';
import NewspaperOutlineIcon from '@/Icons/NewspaperOutlineIcon';

/**
 * @param {FeedWithCategory[]} feeds
 * @param props
 * @returns {JSX.Element}
 * @constructor
 */
export default function Index({feeds, ...props}) {
    const {t, tChoice} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Feeds')}</Header>}
            hasMobileSpacing
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

            {feeds.length > 0 ? (
                <LinkStack>
                    {feeds.map((feed) => (
                        <LinkStack.Item
                            key={feed.id}
                            href={route('feeds.edit', feed)}
                            className="block sm:flex justify-between"
                        >
                            <div>
                                <div>
                                    {feed.name}
                                </div>

                                {feed.last_failed_at && (
                                    <div className="text-sm text-pink-500">
                                        {t('feed.last_failed_at', {date: formatDateTime(feed.last_failed_at)})}
                                    </div>
                                )}

                                <div className="text-sm sm:text-base text-muted">
                                    {feed.category.name}
                                </div>
                            </div>

                            <div className="text-sm sm:text-base text-muted">
                                {tChoice('feed.feed_items_count', feed.feed_items_count)}
                            </div>
                        </LinkStack.Item>
                    ))}
                </LinkStack>
            ) : (
                <EmptyState
                    icon={NewspaperOutlineIcon}
                    message={t('No feeds.')}
                    description={t('Get started by creating a new feed.')}
                />
            )}
        </AuthenticatedLayout>
    );
}
