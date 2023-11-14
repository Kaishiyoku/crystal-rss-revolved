import {useForm, usePage} from '@inertiajs/react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import Select from '@/Components/Select';
import React, {useState} from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Card from '@/Components/Card';
import {PrimaryButton, SecondaryButton} from '@/Components/Button';
import Checkbox from '@/Components/Checkbox';
import {Feed, PageProps, SelectNumberOption} from '@/types';

export default function Form({method, action, feed, categories}: { method: 'post' | 'put'; action: string; feed: Feed; categories: SelectNumberOption[]; }) {
    const {t, tChoice} = useLaravelReactI18n();
    const {monthsAfterPruningFeedItems} = usePage().props as PageProps;
    const [isDiscoverFeedProcessing, setIsDiscoverFeedProcessing] = useState(false);
    const [searchUrl, setSearchUrl] = useState('');
    const [discoveredFeedUrls, setDiscoveredFeedUrls] = useState([]);

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

        window.axios.post(route('discover-feed-urls'), {feed_url: searchUrl})
            .then((response) => {
                setDiscoveredFeedUrls(response.data);
            })
            .catch((error) => {
                console.error(error);
            })
            .finally(() => setIsDiscoverFeedProcessing(false));
    };

    const selectDiscoveredFeedUrl = (feedUrl: string) => () => {
        setIsDiscoverFeedProcessing(true);

        window.axios.post(route('discover-feed'), {feed_url: feedUrl})
            .then((response) => {
                setData({...data, ...response.data});

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
            <div className="flex">
                <TextInput
                    id="search_url"
                    className="grow w-full rounded-r-none border-r-0"
                    placeholder={t('Search URL...')}
                    value={searchUrl}
                    onChange={(e) => setSearchUrl(e.target.value)}
                    isFocused
                />

                <SecondaryButton
                    className="rounded-l-none bg-white dark:bg-gray-900"
                    onClick={() => discoverFeedUrls(searchUrl)}
                    disabled={isDiscoverFeedProcessing || searchUrl.length < 5}
                >
                    {t('Search')}
                </SecondaryButton>
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

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="category_id" value={t('validation.attributes.category_id')} required/>

                    <Select
                        id="category_id"
                        className="mt-1 block w-full"
                        value={data.category_id}
                        options={categories}
                        onChange={(event: React.FormEvent<HTMLSelectElement>) => setData('category_id', parseInt(event.currentTarget.value, 10))}
                        disabled={isDiscoverFeedProcessing}
                        required
                    />

                    <InputError className="mt-2" message={errors.category_id}/>
                </div>

                <div>
                    <InputLabel htmlFor="feed_url" value={t('validation.attributes.feed_url')} required/>

                    <TextInput
                        id="feed_url"
                        className="mt-1 block w-full"
                        value={data.feed_url}
                        onChange={(e) => setData('feed_url', e.target.value)}
                        disabled={isDiscoverFeedProcessing}
                        required
                    />

                    <InputError className="mt-2" message={errors.feed_url}/>
                </div>

                <div>
                    <InputLabel htmlFor="site_url" value={t('validation.attributes.site_url')} required/>

                    <TextInput
                        id="site_url"
                        className="mt-1 block w-full"
                        value={data.site_url}
                        onChange={(e) => setData('site_url', e.target.value)}
                        disabled={isDiscoverFeedProcessing}
                        required
                    />

                    <InputError className="mt-2" message={errors.site_url}/>
                </div>

                <div>
                    <InputLabel htmlFor="favicon_url" value={t('validation.attributes.favicon_url')}/>

                    <TextInput
                        id="favicon_url"
                        className="mt-1 block w-full"
                        value={data.favicon_url}
                        onChange={(e) => setData('favicon_url', e.target.value)}
                        disabled={isDiscoverFeedProcessing}
                    />

                    <InputError className="mt-2" message={errors.site_url}/>
                </div>

                <div>
                    <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>

                    <TextInput
                        id="name"
                        className="mt-1 block w-full"
                        value={data.name}
                        onChange={(e) => setData('name', e.target.value)}
                        disabled={isDiscoverFeedProcessing}
                        required
                    />

                    <InputError className="mt-2" message={errors.name}/>
                </div>

                <div>
                    <InputLabel htmlFor="language" value={t('validation.attributes.language')} required/>

                    <TextInput
                        id="language"
                        className="mt-1 block w-full"
                        value={data.language}
                        onChange={(e) => setData('language', e.target.value)}
                        disabled={isDiscoverFeedProcessing}
                        required
                    />

                    <InputError className="mt-2" message={errors.name}/>
                </div>

                <div>
                    <label className="flex items-center w-fit">
                        <Checkbox
                            name="is_purgeable"
                            checked={data.is_purgeable}
                            value={data.is_purgeable}
                            disabled={isDiscoverFeedProcessing}
                            onChange={(e) => setData('is_purgeable', e.currentTarget.checked)}
                        />
                        <span className="ml-2 text-sm text-gray-600 dark:text-gray-400">{tChoice('feed.purge', monthsAfterPruningFeedItems)}</span>
                    </label>
                </div>

                <div className="flex items-center gap-4">
                    <PrimaryButton type="submit" disabled={processing || isDiscoverFeedProcessing}>
                        {t('Save')}
                    </PrimaryButton>
                </div>
            </form>
        </>
    );
}
