import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import InputError from '@/Components/Form/InputError';
import {PrimaryButton, SecondaryButton} from '@/Components/Button';
import React, {useRef, useState} from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Form} from 'react-router-dom';
import Feed from '@/types/generated/Models/Feed';
import CreateFeedValidationErrors from '@/types/CreateFeedValidationErrors';
import EditFeedValidationErrors from '@/types/EditFeedValidationErrors';
import LinkStack from '@/Components/LinkStack';
import Select from '@/Components/Form/Select';
import Checkbox from '@/Components/Form/Checkbox';
import rq from '@/Core/rq';
import DiscoveredFeed from '@/types/DiscoveredFeed';
import {SelectNumberOption} from '@/types/SelectOption';
import useAuth from '@/Hooks/useAuth';

export default function FeedForm({action, feed = null, categories, errors}: { action: string; feed?: Feed | null; categories: SelectNumberOption[]; errors: CreateFeedValidationErrors | EditFeedValidationErrors; }) {
    const {t, tChoice} = useLaravelReactI18n();

    const {user} = useAuth();

    const [searchUrl, setSearchUrl] = useState('');
    const [discoveredFeedUrls, setDiscoveredFeedUrls] = useState<string[]>([]);
    const [isDiscoverFeedProcessing, setIsDiscoverFeedProcessing] = useState(false);

    const nameRef = useRef<HTMLInputElement>(null);
    const languageRef = useRef<HTMLInputElement>(null);
    const feedUrlRef = useRef<HTMLInputElement>(null);
    const siteUrlRef = useRef<HTMLInputElement>(null);
    const faviconUrlRef = useRef<HTMLInputElement>(null);

    const discoverFeedUrls = (searchUrl: string) => {
        setDiscoveredFeedUrls([]);
        setIsDiscoverFeedProcessing(true);

        rq.post('/api/discover-feed-urls', {json: {feed_url: searchUrl}})
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

        rq.post('/api/discover-feed', {json: {feed_url: feedUrl}})
            .json<DiscoveredFeed>()
            .then((responseData) => {
                nameRef.current!.value = responseData.name;
                languageRef.current!.value = responseData.language;
                feedUrlRef.current!.value = responseData.feed_url;
                siteUrlRef.current!.value = responseData.site_url;
                faviconUrlRef.current!.value = responseData.favicon_url;

                setSearchUrl('');
                setDiscoveredFeedUrls([]);
            })
            .catch((error) => {
                console.error(error);
            })
            .finally(() => setIsDiscoverFeedProcessing(false));
    };

    return (
        <Form method="post" action={action} className="space-y-4">
            <div className="flex pb-8">
                <TextInput
                    id="search_url"
                    className="grow w-full rounded-r-none border-r-0"
                    placeholder={t('Search URL...')}
                    value={searchUrl}
                    onChange={(e) => setSearchUrl(e.target.value)}
                    isFocused
                />

                <SecondaryButton
                    className="rounded-l-none border border-gray-300 dark:border-gray-700"
                    onClick={() => discoverFeedUrls(searchUrl)}
                    disabled={isDiscoverFeedProcessing || searchUrl.length < 5}
                >
                    {t('Search')}
                </SecondaryButton>
            </div>

            {discoveredFeedUrls.length > 0 && (
                <LinkStack>
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
                </LinkStack>
            )}

            <div>
                <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>
                <TextInput
                    ref={nameRef}
                    id="name"
                    name="name"
                    defaultValue={feed?.name}
                    className="block w-full"
                    required
                    isFocused
                />
                <InputError message={errors?.name}/>
            </div>

            <div>
                <InputLabel htmlFor="category_id" value={t('validation.attributes.category_id')} required/>

                <Select
                    id="category_id"
                    name="category_id"
                    defaultValue={feed?.category_id}
                    className="mt-1 block w-full"
                    options={categories}
                    required
                />

                <InputError message={errors?.category_id}/>
            </div>

            <div>
                <InputLabel htmlFor="language" value={t('validation.attributes.language')} required/>

                <TextInput
                    ref={languageRef}
                    id="language"
                    name="language"
                    defaultValue={feed?.language}
                    className="mt-1 block w-full"
                    required
                />

                <InputError className="mt-2" message={errors?.language}/>
            </div>

            <div>
                <InputLabel htmlFor="feed_url" value={t('validation.attributes.feed_url')} required/>

                <TextInput
                    ref={feedUrlRef}
                    id="feed_url"
                    name="feed_url"
                    defaultValue={feed?.feed_url}
                    className="mt-1 block w-full"
                    required
                />

                <InputError className="mt-2" message={errors?.feed_url}/>
            </div>

            <div>
                <InputLabel htmlFor="site_url" value={t('validation.attributes.site_url')} required/>

                <TextInput
                    ref={siteUrlRef}
                    id="site_url"
                    name="site_url"
                    defaultValue={feed?.site_url}
                    className="mt-1 block w-full"
                    required
                />

                <InputError className="mt-2" message={errors?.site_url}/>
            </div>

            <div>
                <InputLabel htmlFor="favicon_url" value={t('validation.attributes.favicon_url')}/>

                <TextInput
                    ref={faviconUrlRef}
                    id="favicon_url"
                    name="favicon_url"
                    defaultValue={feed?.favicon_url ?? ''}
                    className="mt-1 block w-full"
                />

                <InputError className="mt-2" message={errors?.site_url}/>
            </div>

            <div>
                <label className="flex items-center w-fit">
                    <Checkbox name="is_purgeable" value="1" defaultChecked={feed?.is_purgeable}/>

                    <span className="ml-2 text-sm text-gray-600 dark:text-gray-400">
                        {tChoice('feed.purge', user!.months_after_pruning_feed_items)}
                    </span>
                </label>
            </div>

            <PrimaryButton type="submit">
                {t('Save')}
            </PrimaryButton>
        </Form>
    );
}
