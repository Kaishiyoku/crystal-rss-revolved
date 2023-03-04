import {useForm} from '@inertiajs/react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import Select from '@/Components/Select';
import {Transition} from '@headlessui/react';
import SecondaryButton from '@/Components/SecondaryButton';
import {useState} from 'react';

export default function Form({method, action, feed, categories}) {
    const [isDiscoverFeedProcessing, setIsDiscoverFeedProcessing] = useState(false);
    const [searchUrl, setSearchUrl] = useState('');
    const [discoveredFeedUrls, setDiscoveredFeedUrls] = useState([]);

    const {data, setData, post, put, errors, processing, recentlySuccessful} = useForm({
        category_id: feed.category_id ?? categories[0].value,
        feed_url: feed.feed_url ?? '',
        site_url: feed.site_url ?? '',
        favicon_url: feed.favicon_url ?? '',
        name: feed.name ?? '',
        language: feed.language ?? '',
    });

    const discoverFeedUrls = (searchUrl) => {
        setDiscoveredFeedUrls([]);
        setIsDiscoverFeedProcessing(true);

        axios.post(route('discover-feed-urls'), {feed_url: searchUrl})
            .then((response) => {
                setDiscoveredFeedUrls(response.data);
            })
            .catch((error) => {
                console.error(error);
            })
            .finally(() => setIsDiscoverFeedProcessing(false));
    };

    const selectDiscoveredFeedUrl = (feedUrl) => () => {
        setIsDiscoverFeedProcessing(true)

        axios.post(route('discover-feed'), {feed_url: feedUrl})
            .then((response) => {
                setData({...data, ...response.data})

                setSearchUrl('');
                setDiscoveredFeedUrls([]);
            })
            .catch((error) => {
                console.error(error);
            })
            .finally(() => setIsDiscoverFeedProcessing(false));
    };

    const submit = (e) => {
        e.preventDefault();

        const request = method === 'post' ? post : put;

        request(action);
    };

    return (
        <>
            <div className="flex">
                <div className="relative grow">
                    <TextInput
                        id="search_url"
                        className="grow w-full rounded-r-none border-r-0"
                        placeholder="Search URL"
                        value={searchUrl}
                        onChange={(e) => setSearchUrl(e.target.value)}
                        isFocused
                    />

                    <Transition
                        show={isDiscoverFeedProcessing}
                        enterFrom="opacity-0"
                        leaveTo="opacity-0"
                        className="transition ease-in-out"
                    >
                        <svg className="absolute right-0 top-2.5 animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg"
                             fill="none" viewBox="0 0 24 24">
                            <circle className="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                    strokeWidth="4"></circle>
                            <path className="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </Transition>
                </div>

                <SecondaryButton
                    className="rounded-l-none"
                    onClick={() => discoverFeedUrls(searchUrl)}
                    disabled={isDiscoverFeedProcessing || searchUrl.length < 5}
                >
                    Search
                </SecondaryButton>
            </div>

            {discoveredFeedUrls.length > 0 && (
                <div className="mt-4 divide-y dark:divide-gray-700">
                    {discoveredFeedUrls.map((discoveredFeedUrl) => (
                        <button key={discoveredFeedUrl} type="button" className="block w-full text-left px-4 py-2 dark:text-gray-300 bg-gray-800 dark:hover:bg-gray-600 first:rounded-t last:rounded-b" onClick={selectDiscoveredFeedUrl(discoveredFeedUrl)}>
                            {discoveredFeedUrl}
                        </button>
                    ))}
                </div>
            )}

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <InputLabel htmlFor="category_id" value="Category" required/>

                    <Select
                        id="category_id"
                        className="mt-1 block w-full"
                        value={data.category_id}
                        options={categories}
                        onChange={(e) => setData('category_id', e.target.value)}
                        disabled={isDiscoverFeedProcessing}
                        required
                    />

                    <InputError className="mt-2" message={errors.category_id}/>
                </div>

                <div>
                    <InputLabel htmlFor="feed_url" value="Feed URL" required/>

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
                    <InputLabel htmlFor="site_url" value="Site URL" required/>

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
                    <InputLabel htmlFor="favicon_url" value="Favicon URL" required/>

                    <TextInput
                        id="favicon_url"
                        className="mt-1 block w-full"
                        value={data.favicon_url}
                        onChange={(e) => setData('favicon_url', e.target.value)}
                        disabled={isDiscoverFeedProcessing}
                        required
                    />

                    <InputError className="mt-2" message={errors.site_url}/>
                </div>

                <div>
                    <InputLabel htmlFor="name" value="Name" required/>

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
                    <InputLabel htmlFor="language" value="Language" required/>

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

                <div className="flex items-center gap-4">
                    <PrimaryButton disabled={processing || isDiscoverFeedProcessing}>Save</PrimaryButton>
                </div>
            </form>
        </>
    );
}
