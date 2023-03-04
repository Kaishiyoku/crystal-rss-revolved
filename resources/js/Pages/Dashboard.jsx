import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link, router} from '@inertiajs/react';
import Header from '@/Components/Page/Header';
import {useState} from 'react';
import FeedItemCard from '@/Components/FeedItemCard';
import Dropdown from '@/Components/Dropdown';

export default function Dashboard(props) {
    const [allFeedItems, setAllFeedItems] = useState(props.feedItems.data);

    const header = (
        <div>
            <Header>Dashboard</Header>
            <div>{props.totalNumberOfFeedItems}</div>
        </div>
    );

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={header}
        >
            <Head title="Dashboard" />

            <div className="pb-5">
                <Dropdown>
                    <Dropdown.Trigger>
                        <span className="inline-flex rounded-md">
                            <button
                                type="button"
                                className="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150"
                            >
                                Filter by feed

                                <svg
                                    className="ml-2 -mr-0.5 h-4 w-4"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 20 20"
                                    fill="currentColor"
                                >
                                    <path
                                        fillRule="evenodd"
                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                        clipRule="evenodd"
                                    />
                                </svg>
                            </button>
                        </span>
                    </Dropdown.Trigger>

                    <Dropdown.Content align="left">
                        {props.unreadFeeds.map((unreadFeed) => (
                            <Dropdown.Link
                                key={unreadFeed.id}
                                href={`${route('dashboard')}?feed_id=${unreadFeed.id}`}
                            >
                                {unreadFeed.name} ({unreadFeed.feed_items_count})
                            </Dropdown.Link>
                        ))}
                    </Dropdown.Content>
                </Dropdown>
            </div>

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
                        Load more
                    </Link>
                )}
            </div>
        </AuthenticatedLayout>
    );
}
