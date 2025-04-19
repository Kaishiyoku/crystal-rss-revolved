import { useForm, usePage } from '@inertiajs/react';
import type React from 'react';
import { useState } from 'react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Button } from '@/Components/Button';
import type { PageProps } from '@/types';
import type { SelectNumberOption } from '@/types/SelectOption';
import  DiscoveredFeed from '@/types/DiscoveredFeed';
import { Input } from '@/Components/Form/Input';
import { ErrorMessage, Field, FieldGroup, Label } from '@/Components/Fieldset';
import { Checkbox, CheckboxField } from '@/Components/Form/Checkbox';
import InputListbox from '@/Components/Form/InputListbox';
import toNumber from '@/Utils/toNumber';
import { LinkStack, LinkStackItem } from '@/Components/LinkStack';
import type { Feed } from '@/types/generated/models';
import {Subheading} from '@/Components/Heading';
import discoveredFeed from '@/types/DiscoveredFeed';

export default function Form({
	method,
	action,
	feed,
	categories,
}: {
	method: 'post' | 'put';
	action: string;
	feed: Feed;
	categories: SelectNumberOption[];
}) {
	const { t, tChoice } = useLaravelReactI18n();
	const { monthsAfterPruningFeedItems } = usePage<PageProps>().props;
	const [isDiscoverFeedProcessing, setIsDiscoverFeedProcessing] =
		useState(false);
	const [searchUrl, setSearchUrl] = useState('');
	const [discoveredFeedUrls, setDiscoveredFeedUrls] = useState<string[]>([]);
    const [selectedFeedUrl, setSelectedFeedUrl] = useState<string | null>(null);
    const [showManualInputFields, setShowManualInputFields] = useState(false);

	const { data, setData, post, put, errors, processing, isDirty } = useForm({
		category_id: feed.category_id ?? categories[0].value,
		feed_url: feed.feed_url ?? '',
		site_url: feed.site_url ?? '',
		favicon_url: feed.favicon_url ?? '',
		name: feed.name ?? '',
		language: feed.language ?? '',
		is_purgeable: feed.is_purgeable ?? true,
	});

	const discoverFeedUrls = (searchUrl: string) => {
		setDiscoveredFeedUrls([]);
		setIsDiscoverFeedProcessing(true);

		window.ky
			.post(route('discover-feed-urls'), { json: { feed_url: searchUrl } })
			.json<string[]>()
			.then((data) => {
				setDiscoveredFeedUrls(data);
			})
			.catch((error) => {
				console.error(error);
			})
			.finally(() => setIsDiscoverFeedProcessing(false));
	};

	const selectDiscoveredFeedUrl = (feedUrl: string) => () => {
		setIsDiscoverFeedProcessing(true);

		window.ky
			.post(route('discover-feed'), { json: { feed_url: feedUrl } })
			.json<DiscoveredFeed>()
			.then((responseData) => {
				setData({ ...data, ...responseData });

				setSearchUrl('');
				setDiscoveredFeedUrls([]);
                setSelectedFeedUrl(responseData.feed_url);
			})
			.catch((error) => {
                setSelectedFeedUrl(null);

				console.error(error);
			})
			.finally(() => setIsDiscoverFeedProcessing(false));
	};

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		const request = method === 'post' ? post : put;

		request(action);
	};

    const handleAddFeedManually = () => {
        setDiscoveredFeedUrls([]);
        setShowManualInputFields(true);
    };

    const handleAddFeedViaDiscovery = () => {
        setShowManualInputFields(false);
    };

	return (
		<>
			<div className="flex space-x-2 pb-8">
				<Input
					id="search_url"
					className="grow w-full"
					placeholder={t('Search URL...')}
					value={searchUrl}
					onChange={(e) => setSearchUrl(e.target.value)}
					disabled={isDiscoverFeedProcessing || showManualInputFields}
					autoFocus
				/>

				<Button
					onClick={() => discoverFeedUrls(searchUrl)}
					disabled={isDiscoverFeedProcessing || showManualInputFields || searchUrl.length < 5}
				>
					{t('Search')}
				</Button>
			</div>

			{discoveredFeedUrls.length > 0 && (
                <div className="space-y-2">
                    <Subheading>
                        {t('Found feeds')}
                    </Subheading>

                    <LinkStack className="mb-8">
                        {discoveredFeedUrls.map((discoveredFeedUrl) => (
                            <LinkStackItem
                                key={discoveredFeedUrl}
                                title={discoveredFeedUrl}
                                onClick={selectDiscoveredFeedUrl(discoveredFeedUrl)}
                                disabled={isDiscoverFeedProcessing}
                            />
                        ))}
                    </LinkStack>
                </div>
			)}

            {!selectedFeedUrl && (
                <div className="flex items-center space-x-2 my-4">
                    <div className="border dark:border-zinc-700 grow" />

                    {showManualInputFields
                        ? (
                            <Button onClick={handleAddFeedViaDiscovery} plain>
                                {t("Search URL...")}
                            </Button>
                        )
                        : (
                            <Button onClick={handleAddFeedManually} plain>
                                {t("Add feed manually")}
                            </Button>
                        )}

                    <div className="border dark:border-zinc-700 grow" />
                </div>
            )}

			<form onSubmit={submit}>
				<FieldGroup hidden={!showManualInputFields && !isDirty}>
					<div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
						<Field disabled={isDiscoverFeedProcessing}>
							<Label htmlFor="name" required>
								{t('validation.attributes.name')}
							</Label>
							<Input
								id="name"
								className="mt-1 block w-full"
								value={data.name}
								onChange={(e) => setData('name', e.target.value)}
								required
							/>
							<ErrorMessage>{errors.name}</ErrorMessage>
						</Field>

						<Field disabled={isDiscoverFeedProcessing}>
							<Label htmlFor="category_id" required>
								{t('validation.attributes.category_id')}
							</Label>
							<InputListbox
								name="category_id"
								defaultValue={data.category_id}
								options={categories}
								onChange={(value) => setData('category_id', toNumber(value))}
								disabled={isDiscoverFeedProcessing}
							/>
							<ErrorMessage>{errors.category_id}</ErrorMessage>
						</Field>

						<Field disabled={isDiscoverFeedProcessing}>
							<Label htmlFor="language" required>
								{t('validation.attributes.language')}
							</Label>
							<Input
								id="language"
								className="mt-1 block w-full"
								value={data.language}
								onChange={(e) => setData('language', e.target.value)}
								required
							/>
							<ErrorMessage>{errors.language}</ErrorMessage>
						</Field>
					</div>

					<div className="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
						<Field disabled={isDiscoverFeedProcessing}>
							<Label htmlFor="feed_url" required>
								{t('validation.attributes.feed_url')}
							</Label>
							<Input
								id="feed_url"
								className="mt-1 block w-full"
								value={data.feed_url}
								onChange={(e) => setData('feed_url', e.target.value)}
								required
							/>
							<ErrorMessage>{errors.feed_url}</ErrorMessage>
						</Field>

						<Field disabled={isDiscoverFeedProcessing}>
							<Label htmlFor="site_url" required>
								{t('validation.attributes.site_url')}
							</Label>
							<Input
								id="site_url"
								className="mt-1 block w-full"
								value={data.site_url}
								onChange={(e) => setData('site_url', e.target.value)}
								required
							/>
							<ErrorMessage>{errors.site_url}</ErrorMessage>
						</Field>

						<Field disabled={isDiscoverFeedProcessing}>
							<Label htmlFor="favicon_url">
								{t('validation.attributes.favicon_url')}
							</Label>
							<Input
								id="favicon_url"
								className="mt-1 block w-full"
								value={data.favicon_url}
								onChange={(e) => setData('favicon_url', e.target.value)}
							/>
							<ErrorMessage>{errors.favicon_url}</ErrorMessage>
						</Field>
					</div>

					<CheckboxField disabled={isDiscoverFeedProcessing}>
						<Checkbox
							name="is_purgeable"
							checked={data.is_purgeable}
							onChange={(checked) => setData('is_purgeable', checked)}
						/>
						<Label>{tChoice('feed.purge', monthsAfterPruningFeedItems)}</Label>
					</CheckboxField>

					<Button
						type="submit"
						disabled={processing || isDiscoverFeedProcessing}
					>
						{t('Save')}
					</Button>
				</FieldGroup>
			</form>
		</>
	);
}
