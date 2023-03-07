import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router} from '@inertiajs/react';
import Header from '@/Components/Page/Header';
import {useState} from 'react';
import FeedItemCard from '@/Components/FeedItemCard';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import FeedFilterDropdown from '@/Components/FeedFilterDropdown';
import SecondaryButton from '@/Components/SecondaryButton';

export default function Dashboard(props) {
    const {t, tChoice} = useLaravelReactI18n();
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);

    const header = (
        <div>
            <Header>{t('Dashboard')}</Header>
            <div className="text-muted">
                {tChoice('dashboard.unread_articles', props.totalNumberOfFeedItems)}
            </div>
        </div>
    );

    const markAllAsRead = async () => {
        await axios.put(route('mark-all-as-read'));

        router.get(route('dashboard'));
    };

    const actions = (
        <>
            {props.totalNumberOfFeedItems > 0 && (
                <SecondaryButton confirm onClick={markAllAsRead}>
                    {t('Mark all as read')}
                </SecondaryButton>
            )}
        </>
    );

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={header}
            actions={actions}
        >
            <Head title="Dashboard"/>

            <FeedFilterDropdown feeds={props.unreadFeeds}/>

            <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-16 sm:gap-y-4">
                {allFeedItems.map((feedItem) => (
                    <FeedItemCard
                        key={feedItem.id}
                        feedItem={feedItem}
                    />
                ))}
            </div>

            <div className="pt-5 px-4 sm:px-0">
                {props.feedItems.next_cursor && (
                    <Link
                        className="link-secondary"
                        href={props.feedItems.next_page_url}
                        only={['feedItems']}
                        onSuccess={(state) => setAllFeedItems([...allFeedItems, ...state.props.feedItems.data])}
                        preserveState
                        preserveScroll
                    >
                        {t('Load more')}
                    </Link>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
