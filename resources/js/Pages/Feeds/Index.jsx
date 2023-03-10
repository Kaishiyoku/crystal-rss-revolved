import {Link, Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import LinkListCardContainer from '@/Components/LinkListCardContainer';
import CardLink from '@/Components/CardLink';

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
            withMobileSpacing
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

            <LinkListCardContainer>
                {feeds.map((feed) => (
                    <CardLink
                        key={feed.id}
                        href={route('feeds.edit', feed)}
                        className="sm:flex justify-between"
                    >
                        <div>
                            <div>
                                {feed.name}
                            </div>

                            <div className="text-sm sm:text-base text-muted">
                                {feed.category.name}
                            </div>
                        </div>

                        <div className="text-sm sm:text-base text-muted">
                            {tChoice('feed.feed_items_count', feed.feed_items_count)}
                        </div>
                    </CardLink>
                ))}
            </LinkListCardContainer>
        </AuthenticatedLayout>
    );
}
