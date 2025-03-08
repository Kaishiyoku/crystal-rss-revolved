import {useState} from 'react';
import clsx from 'clsx';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Button} from '@/Components/Button';
import formatDateTime from '@/Utils/formatDateTime';
import {RouteParams} from 'ziggy-js';
import {ArrowTopRightOnSquareIcon, CalendarDaysIcon, EyeIcon, EyeSlashIcon, RssIcon} from '@heroicons/react/20/solid';
import {ImagePlaceholder, ImageWithBlurHash} from '@/Components/Image';
import {FeedItem} from '@/types/generated/models';
import {Heading} from '@/Components/Heading';
import {useSetAtom} from 'jotai';
import {unreadFeedsAtom, updateUnreadFeedByFeedItem} from '@/Stores/unreadFeedsAtom';

export default function FeedItemCard({feedItem}: { feedItem: FeedItem; }) {
    const {t} = useLaravelReactI18n();
    const [internalFeedItem, setInternalFeedItem] = useState(feedItem);
    const [processing, setProcessing] = useState(false);

    const setUnreadFeedsAtomValue = useSetAtom(unreadFeedsAtom);

    const toggle = () => {
        setProcessing(true);

        void window.ky.put(route('toggle-feed-item', internalFeedItem as unknown as RouteParams<'toggle-feed-item'>))
            .json<FeedItem>()
            .then((updatedFeedItem) => {
                setUnreadFeedsAtomValue(updateUnreadFeedByFeedItem(updatedFeedItem));

                setInternalFeedItem(updatedFeedItem);
            })
            .finally(() => setProcessing(false));
    };

    return (
        <div
            key={internalFeedItem.id}
            className={clsx('@container p-4 ring-1 ring-zinc-950/10 dark:ring-zinc-50/10 rounded-lg transition ease-out duration-300', {'opacity-50': internalFeedItem.read_at})}
        >
            <div className="flex flex-col @md:flex-row h-full">
                <div className="flex flex-col grow-0 mb-4 @md:mb-0 @md:mr-4">
                    <div className="pb-4">
                        {internalFeedItem.has_image && internalFeedItem.image_url
                            ? (
                                <ImageWithBlurHash
                                    src={internalFeedItem.image_url}
                                    alt=""
                                    blurHash={internalFeedItem.blur_hash}
                                    className="w-full @md:w-44 aspect-3/2 rounded-lg"
                                />
                            )
                            : (
                                <ImagePlaceholder className="w-full @md:w-44 aspect-3/2 rounded-lg"/>
                            )}
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
                                        className="size-4 rounded-full"
                                    />
                                )
                                : <RssIcon className="size-5"/>}

                            <div>
                                {internalFeedItem.feed.name}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="grow flex flex-col min-w-0">
                    <Heading
                        level={2}
                    >
                        {internalFeedItem.title}
                    </Heading>

                    {internalFeedItem.description && (
                        <div className="grow">
                            <div className="text-muted overflow-hidden line-clamp-6 xl:line-clamp-3 hyphens-auto break-words" lang={internalFeedItem.feed.language}>
                                {internalFeedItem.description}
                            </div>
                        </div>
                    )}

                    <div className="grow flex items-end lg:justify-end space-x-2 pt-4">
                        <Button
                            onClick={toggle}
                            disabled={processing}
                            className="w-full lg:w-auto"
                            plain
                        >
                            {internalFeedItem.read_at
                                ? <EyeSlashIcon/>
                                : <EyeIcon/>}

                            {internalFeedItem.read_at ? t('Read') : t('Unread')}
                        </Button>

                        <Button
                            href={feedItem.url}
                            className="w-full lg:w-auto"
                            plain
                            external
                        >
                            <ArrowTopRightOnSquareIcon/>

                            {t('Read article')}
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    );
}
