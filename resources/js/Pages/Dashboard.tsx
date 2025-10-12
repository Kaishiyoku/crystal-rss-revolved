import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, InfiniteScroll } from '@inertiajs/react';
import FeedItemCard from '@/Components/FeedItemCard';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { EmptyState } from '@/Components/EmptyState';
import type { PageProps } from '@/types';
import type CursorPagination from '@/types/CursorPagination';
import MarkAllAsReadButton from '@/Components/MarkAllAsReadButton';
import { NewspaperIcon } from '@heroicons/react/24/outline';
import type { FeedItem } from '@/types/generated/models';
import { useAtomValue } from 'jotai';
import { totalNumberOfFeedItemsAtom } from '@/Stores/unreadFeedsAtom';
import LoadingIcon from '@/Components/Icons/LoadingIcon';

type DashboardPageProps = PageProps & {
	feedItems: CursorPagination<FeedItem>;
};

export default function Dashboard({ feedItems, ...props }: DashboardPageProps) {
	const { t, tChoice } = useLaravelReactI18n();

	const totalNumberOfFeedItemsAtomValue = useAtomValue(
		totalNumberOfFeedItemsAtom,
	);

	return (
		<AuthenticatedLayout
			auth={props.auth}
			errors={props.errors}
			header={
				<>
					{t('Dashboard')}

					<small className="text-muted pl-2">
						{tChoice(
							'dashboard.unread_articles',
							totalNumberOfFeedItemsAtomValue,
						)}
					</small>
				</>
			}
			actions={totalNumberOfFeedItemsAtomValue > 0 && <MarkAllAsReadButton />}
		>
			<Head title="Dashboard" />

			{feedItems.data.length === 0 && (
				<EmptyState
					icon={NewspaperIcon}
					message={t('No unread articles.')}
					description={t('Come back later.')}
				/>
			)}

			<InfiniteScroll
				data="feedItems"
				buffer={250}
				loading={() => (
					<div className="flex justify-center pt-6">
						<LoadingIcon />
					</div>
				)}
				className="grid sm:grid-cols-2 gap-6"
			>
				{feedItems.data.map((feedItem) => (
					<FeedItemCard key={feedItem.id} feedItem={feedItem} />
				))}
			</InfiniteScroll>
		</AuthenticatedLayout>
	);
}
