import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router} from '@inertiajs/react';
import Header from '@/Components/Page/Header';
import {useState} from 'react';
import FeedItemCard from '@/Components/FeedItemCard';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import FeedFilterDropdown from '@/Components/FeedFilterDropdown';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';
import Actions from '@/Components/Actions';
import {SecondaryButton} from '@/Components/Button';
import NewspaperOutlineIcon from '@/Icons/NewspaperOutlineIcon';
import EmptyState from '@/Components/EmptyState';
import EyeOutlineIcon from '@/Icons/EyeOutlineIcon';

export default function Dashboard(props) {
    const {t, tChoice} = useLaravelReactI18n();
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);
    const [totalNumberOfFeedItems, setTotalNumberOfFeedItems] = useState(props.totalNumberOfFeedItems);

    const markAllAsRead = async () => {
        await axios.put(route('mark-all-as-read'));

        router.get(route('dashboard'));
    };

    return (
        <TotalNumberOfFeedItemsContext.Provider value={[totalNumberOfFeedItems, setTotalNumberOfFeedItems]}>
            <AuthenticatedLayout
                auth={props.auth}
                errors={props.errors}
                header={
                    <Header subTitle={tChoice('dashboard.unread_articles', totalNumberOfFeedItems)}>
                        {t('Dashboard')}
                    </Header>
                }
            >
                <Head title="Dashboard"/>

                <div className="sm:flex sm:justify-end px-4 sm:px-0 mb-8">
                    <FeedFilterDropdown selectedFeed={props.selectedFeed} feeds={props.unreadFeeds}/>

                    {totalNumberOfFeedItems > 0 && (
                        <SecondaryButton
                            confirm
                            onClick={markAllAsRead}
                            icon={EyeOutlineIcon}
                            className="sm:ml-2 mt-1 sm:mt-0"
                            hasMobileFullSize
                        >
                            {t('Mark all as read')}
                        </SecondaryButton>
                    )}
                </div>

                {allFeedItems.length > 0
                    ? (
                        <div className="grid sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-x-4 gap-y-16 sm:gap-y-4">
                            {allFeedItems.map((feedItem, index) => (
                                <FeedItemCard
                                    key={feedItem.id}
                                    hueRotationIndex={index % 6}
                                    feedItem={feedItem}
                                />
                            ))}
                        </div>
                    )
                    : (
                        <EmptyState
                            icon={NewspaperOutlineIcon}
                            message={t('No unread articles.')}
                            description={t('Come back later.')}
                        />
                    )}

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
        </TotalNumberOfFeedItemsContext.Provider>
    );
}
