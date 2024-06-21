import {useForm, usePage} from '@inertiajs/react';
import Select from '@/Components/Form/Select';
import React, {useState} from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Card from '@/Components/Card';
import {Button} from '@/Components/Button';
import {PageProps} from '@/types';
import {SelectNumberOption} from '@/types/SelectOption';
import DiscoveredFeed from '@/types/DiscoveredFeed';
import Feed from '@/types/generated/Models/Feed';
import {Input} from '@/Components/Form/Input';
import {ErrorMessage, Field, FieldGroup, Label} from '@/Components/Fieldset';
import {Checkbox, CheckboxField} from '@/Components/Form/Checkbox';
import InputListbox from '@/Components/Form/InputListbox';
import toNumber from '@/Utils/toNumber';

export default function Form({method, action, feed, categories}: { method: 'post' | 'put'; action: string; feed: Feed; categories: SelectNumberOption[]; }) {
    const {t, tChoice} = useLaravelReactI18n();
    const {monthsAfterPruningFeedItems} = usePage().props as PageProps;
    const [isDiscoverFeedProcessing, setIsDiscoverFeedProcessing] = useState(false);
    const [searchUrl, setSearchUrl] = useState('');
    const [discoveredFeedUrls, setDiscoveredFeedUrls] = useState<string[]>([]);

    const {data, setData, post, put, errors, processing} = useForm({
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

        window.ky.post(route('discover-feed-urls'), {json: {feed_url: searchUrl}})
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

        window.ky.post(route('discover-feed'), {json: {feed_url: feedUrl}})
            .json<DiscoveredFeed>()
            .then((responseData) => {
                setData({...data, ...responseData});

                setSearchUrl('');
                setDiscoveredFeedUrls([]);
            })
            .catch((error) => {
                console.error(error);
            })
            .finally(() => setIsDiscoverFeedProcessing(false));
    };

    const submit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        const request = method === 'post' ? post : put;

        request(action);
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
                    autoFocus
                />

                <Button
                    onClick={() => discoverFeedUrls(searchUrl)}
                    disabled={isDiscoverFeedProcessing || searchUrl.length < 5}
                >
                    {t('Search')}
                </Button>
            </div>

            {discoveredFeedUrls.length > 0 && (
                <Card className="mt-4 divide-y dark:divide-gray-700">
                    {discoveredFeedUrls.map((discoveredFeedUrl) => (
                        <button
                            key={discoveredFeedUrl}
                            type="button"
                            className="block w-full text-left px-4 py-2 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-600 first:rounded-t last:rounded-b transition"
                            onClick={selectDiscoveredFeedUrl(discoveredFeedUrl)}
                        >
                            {discoveredFeedUrl}
                        </button>
                    ))}
                </Card>
            )}

            <form onSubmit={submit}>
                <FieldGroup>
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
                            <ErrorMessage>
                                {errors.name}
                            </ErrorMessage>
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
                            <ErrorMessage>
                                {errors.category_id}
                            </ErrorMessage>
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
                            <ErrorMessage>
                                {errors.language}
                            </ErrorMessage>
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
                            <ErrorMessage>
                                {errors.feed_url}
                            </ErrorMessage>
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
                            <ErrorMessage>
                                {errors.site_url}
                            </ErrorMessage>
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
                            <ErrorMessage>
                                {errors.favicon_url}
                            </ErrorMessage>
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

                    <Button type="submit" disabled={processing || isDiscoverFeedProcessing}>
                        {t('Save')}
                    </Button>
                </FieldGroup>
            </form>
        </>
    );
}
