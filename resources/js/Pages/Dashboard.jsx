import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {Head, Link} from '@inertiajs/react';
import Header from '@/Components/Page/Header';
import {useState} from 'react';
import FeedItemCard from '@/Components/FeedItemCard';

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
