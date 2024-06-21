import {useContext, useState} from 'react';
import clsx from 'clsx';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Button} from '@/Components/Button';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';
import formatDateTime from '@/Utils/formatDateTime';
import {RouteParams} from 'ziggy-js';
import FeedItem from '@/types/generated/Models/FeedItem';
import {CalendarDaysIcon, EyeIcon, EyeSlashIcon, RssIcon} from '@heroicons/react/20/solid';
import {ImagePlaceholder, ImageWithBlurHash} from '@/Components/Image';

export default function FeedItemCard({hueRotationIndex, feedItem}: { hueRotationIndex: number; feedItem: FeedItem; }) {
    const {t} = useLaravelReactI18n();
    const {totalNumberOfFeedItems, setTotalNumberOfFeedItems} = useContext(TotalNumberOfFeedItemsContext);
    const [internalFeedItem, setInternalFeedItem] = useState(feedItem);
    const [processing, setProcessing] = useState(false);

    const toggle = () => {
        setProcessing(true);

        void window.ky.put(route('toggle-feed-item', internalFeedItem as unknown as RouteParams<'toggle-feed-item'>))
            .json<FeedItem>()
            .then((data) => {
                if (data.read_at) {
                    setTotalNumberOfFeedItems(totalNumberOfFeedItems - 1);
                } else {
                    setTotalNumberOfFeedItems(totalNumberOfFeedItems + 1);
                }

                setInternalFeedItem(data);
            })
            .finally(() => setProcessing(false));
    };

    return (
        <div
            key={internalFeedItem.id}
            className={clsx('@container p-4 bg-gray-100 dark:bg-gray-800 rounded-lg transition ease-out duration-300', {'opacity-50': internalFeedItem.read_at})}
        >
            <div className="flex flex-col @md:flex-row">
                <div className="flex flex-col shrink-0 mb-4 @md:mb-0 @md:mr-4">
                    <div className="pb-4">
                        {internalFeedItem.has_image && internalFeedItem.image_url
                            ? (
                                <ImageWithBlurHash
                                    src={internalFeedItem.image_url}
                                    alt={internalFeedItem.title}
                                    blurHash={internalFeedItem.blur_hash}
                                    className="w-full @md:w-44 aspect-[3/2] rounded-lg"
                                />
                            )
                            : (
                                <ImagePlaceholder colorIndex={hueRotationIndex} className="w-full @md:w-44 aspect-[3/2] rounded-lg"/>
                            )
                        }
                    </div>

                    <div className="pb-2 text-sm text-muted">
                        <div className="flex space-x-2 pb-0.5">
                            <CalendarDaysIcon className="size-4"/>
                            <div>{formatDateTime(internalFeedItem.posted_at)}</div>
                        </div>

                        <div className="flex space-x-2 text-sm text-muted">
                            {internalFeedItem.feed.favicon_url
                                ? (
                                    <img
                                        loading="lazy"
                                        src={internalFeedItem.feed.favicon_url}
                                        alt={internalFeedItem.feed.name}
                                        className="size-4 rounded"
                                    />
                                )
                                : <RssIcon className="size-5"/>}

                            <div>
                                {internalFeedItem.feed.name}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="flex flex-col">
                    <a href={internalFeedItem.url} className="inline-block mb-2 text-lg link-blue">
                        {internalFeedItem.title}
                    </a>

                    <div className="grow">
                        <div className="text-muted overflow-hidden line-clamp-6 xl:line-clamp-3 break-all">
                            {internalFeedItem.description}
                        </div>
                    </div>

                    <div className="pt-4">
                        <Button
                            onClick={toggle}
                            disabled={processing}
                            plain
                        >
                            {internalFeedItem.read_at
                                ? <EyeSlashIcon/>
                                : <EyeIcon/>
                            }

                            {internalFeedItem.read_at ? t('Read') : t('Unread')}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );
}
