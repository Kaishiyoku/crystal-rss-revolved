import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import formatDateTime from '@/Utils/formatDateTime';
import { EmptyState } from '@/Components/EmptyState';
import type { PageProps } from '@/types';
import type { RouteParams } from 'ziggy-js';
import { Button } from '@/Components/Button';
import { LinkStack, LinkStackItem } from '@/Components/LinkStack';
import { RssIcon } from '@heroicons/react/20/solid';
import { PlusIcon } from '@heroicons/react/16/solid';
import type FeedWithFeedItemsCount from '@/types/models/FeedWithFeedItemsCount';
import { Description, Label } from '@/Components/Fieldset';
import { Switch, SwitchField } from '@/Components/Form/Switch';
import { useMemo, useState } from 'react';
import { FunnelIcon } from '@heroicons/react/24/outline';

export default function Index({
	feeds,
	...props
}: PageProps & { feeds: FeedWithFeedItemsCount[] }) {
	const { t, tChoice } = useLaravelReactI18n();

	const [showFailedFeedsOnly, setShowFailedFeedsOnly] = useState(false);

	const filteredFeeds = useMemo(
		() =>
			showFailedFeedsOnly
				? feeds.filter((feed) => feed.last_failed_at !== null)
				: feeds,
		[feeds, showFailedFeedsOnly],
	);

	return (
		<AuthenticatedLayout
			auth={props.auth}
			errors={props.errors}
			header={t('Feeds')}
			actions={
				feeds.length > 0 && (
					<Button href={route('feeds.create')} outline>
						{t('Add feed')}
					</Button>
				)
			}
		>
			<Head title={t('Feeds')} />

			{feeds.length > 0 ? (
				<div className="space-y-8">
					<SwitchField className="md:max-w-md">
						<Label>{t('Show failed feeds only')}</Label>
						<Description>
							{t('Only show feeds that failed fetching lately.')}
						</Description>
						<Switch
							name="show_failed_feeds_only"
							checked={showFailedFeedsOnly}
							onChange={(checked) => setShowFailedFeedsOnly(checked)}
						/>
					</SwitchField>

					{showFailedFeedsOnly && filteredFeeds.length === 0 && (
						<EmptyState
							icon={FunnelIcon}
							message={t('No failed feeds.')}
							description={t('There are no feeds that failed fetching lately.')}
						>
							<Button onClick={() => setShowFailedFeedsOnly(false)} outline>
								<FunnelIcon />

								{t('Show all feeds')}
							</Button>
						</EmptyState>
					)}

					{filteredFeeds.length > 0 && (
						<LinkStack>
							{filteredFeeds.map((feed) => (
								<LinkStackItem
									key={feed.id}
									image={
										feed.favicon_url ? (
											<img
												loading="lazy"
												src={feed.favicon_url}
												alt={feed.name}
												className="size-5 rounded-full"
											/>
										) : (
											<RssIcon className="size-5" />
										)
									}
									title={feed.name}
									url={route(
										'feeds.edit',
										feed as unknown as RouteParams<'feeds.edit'>,
									)}
								>
									<div className="text-sm text-muted">
										<div className="flex space-x-2">
											<div>{feed.category.name}</div>
										</div>

										<div className="text-muted">
											{tChoice('feed.feed_items_count', feed.feed_items_count)}
										</div>

										<div className="text-muted">
											{feed.is_purgeable
												? tChoice(
														'feed.purge',
														props.monthsAfterPruningFeedItems,
													)
												: t('feed.no_purge')}
										</div>

										{feed.last_failed_at && (
											<div className="text-pink-500">
												{t('feed.last_failed_at', {
													date: formatDateTime(feed.last_failed_at),
												})}
											</div>
										)}
									</div>
								</LinkStackItem>
							))}
						</LinkStack>
					)}
				</div>
			) : (
				<EmptyState
					icon={RssIcon}
					message={t('No feeds.')}
					description={t('Get started by creating a new feed.')}
				>
					<Button href={route('feeds.create')} outline>
						<PlusIcon />

						{t('Add feed')}
					</Button>
				</EmptyState>
			)}
		</AuthenticatedLayout>
	);
}
