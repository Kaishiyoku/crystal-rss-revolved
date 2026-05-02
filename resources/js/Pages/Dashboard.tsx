import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head, InfiniteScroll, router } from '@inertiajs/react';
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
import { useEffect, useRef, useState } from 'react';
import { useHotkeys } from 'react-hotkeys-hook';

type DashboardPageProps = PageProps & {
	feedItems: CursorPagination<FeedItem>;
};

export default function Dashboard({ feedItems, ...props }: DashboardPageProps) {
	const { t, tChoice } = useLaravelReactI18n();

	const [focusedItemIndex, setFocusedItemIndex] = useState<number | null>(null);

	const feedRefs = useRef<HTMLDivElement[]>([]);

	const focusPrev = () => {
		if (!feedRefs.current) {
			return;
		}

		const prevIndex = focusedItemIndex === null ? 0 : focusedItemIndex - 1;

		if (prevIndex < 0) {
			return;
		}

		const prevRef = feedRefs.current[prevIndex];

		prevRef.focus();

		setFocusedItemIndex(prevIndex);
	};

	const focusNext = () => {
		if (!feedRefs.current) {
			return;
		}

		const nextIndex = focusedItemIndex === null ? 0 : focusedItemIndex + 1;

		if (nextIndex > feedRefs.current.length - 1) {
			return;
		}

		const nextRef = feedRefs.current[nextIndex];

		nextRef.focus();

		setFocusedItemIndex(nextIndex);
	};

	useHotkeys('k', focusPrev);
	useHotkeys('j', focusNext);

	const totalNumberOfFeedItemsAtomValue = useAtomValue(
		totalNumberOfFeedItemsAtom,
	);

	// biome-ignore lint/correctness/useExhaustiveDependencies(feedItems): we want to run this when feedItems change
	useEffect(() => {
		router.reload({ only: ['unreadFeeds'] });
	}, [feedItems]);

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
				preserveUrl
			>
				{feedItems.data.map((feedItem, index) => (
					<FeedItemCard
						key={feedItem.id}
						feedItem={feedItem}
						ref={(el) => {
							feedRefs.current[index] = el;
						}}
					/>
				))}
			</InfiniteScroll>
		</AuthenticatedLayout>
	);
}
