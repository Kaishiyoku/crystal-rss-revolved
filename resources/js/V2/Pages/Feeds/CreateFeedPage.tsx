import {Form, useActionData, useLoaderData} from 'react-router-dom';
import TextInput from '@/Components/TextInput';
import usePageModal from '@/V2/Hooks/usePageModal';
import ValidationErrors from '@/V2/types/ValidationErrors';
import InputError from '@/Components/InputError';
import {PrimaryButton, SecondaryButton} from '@/Components/Button';
import InputLabel from '@/Components/InputLabel';
import React, {useRef, useState} from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Pane, PaneBody, PaneHeader} from '@/Components/Modal/Pane';
import Select from '@/Components/Select';
import Checkbox from '@/Components/Checkbox';
import useAuth from '@/V2/Hooks/useAuth';
import CreateFeedLoaderType from '@/V2/types/CreateFeedLoaderType';
import request from '@/V2/request';
import DiscoveredFeed from '@/types/DiscoveredFeed';
import LinkStack from '@/Components/LinkStack';

type CreateFeedValidationErrors = ValidationErrors & {
    name?: string;
    category_id?: string;
    language?: string;
    feed_url?: string;
    site_url?: string;
    favicon_url?: string;
} | null;

export default function CreateFeedPage() {
    const {t, tChoice} = useLaravelReactI18n();
    const {user} = useAuth();
    const {categories} = useLoaderData() as CreateFeedLoaderType;
    const errors = useActionData() as CreateFeedValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/app/feeds');
    const [isDiscoverFeedProcessing, setIsDiscoverFeedProcessing] = useState(false);
    const [searchUrl, setSearchUrl] = useState('');
    const [discoveredFeedUrls, setDiscoveredFeedUrls] = useState<string[]>([]);

    const nameRef = useRef<HTMLInputElement>(null);
    const languageRef = useRef<HTMLInputElement>(null);
    const feedUrlRef = useRef<HTMLInputElement>(null);
    const siteUrlRef = useRef<HTMLInputElement>(null);
    const faviconUrlRef = useRef<HTMLInputElement>(null);

    const discoverFeedUrls = (searchUrl: string) => {
        setDiscoveredFeedUrls([]);
        setIsDiscoverFeedProcessing(true);

        request.post('/api/discover-feed-urls', {json: {feed_url: searchUrl}})
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

        request.post('/api/discover-feed', {json: {feed_url: feedUrl}})
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
        <Pane appear show={show} onClose={handleClose}>
            <PaneHeader>
                {t('Add feed')}
            </PaneHeader>

            <PaneBody>
                <Form method="post" action="/app/feeds/create" className="space-y-4">
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
                            className="mt-1 block w-full"
                        />

                        <InputError className="mt-2" message={errors?.site_url}/>
                    </div>

                    <div>
                        <label className="flex items-center w-fit">
                            <Checkbox name="is_purgeable" value={true}/>

                            <span className="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                {tChoice('feed.purge', user!.months_after_pruning_feed_items)}
                            </span>
                        </label>
                    </div>

                    <PrimaryButton type="submit">
                        {t('Save')}
                    </PrimaryButton>
                </Form>
            </PaneBody>
        </Pane>
    );
}
