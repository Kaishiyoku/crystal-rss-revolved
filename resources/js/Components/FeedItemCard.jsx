import Card from '@/Components/Card';
import PhotoSolidIcon from '@/Icons/PhotoSolidIcon';
import {useContext, useState} from 'react';
import clsx from 'clsx';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {SecondaryButton} from '@/Components/Button';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';

/**
 * @param {FeedItem} feedItem
 * @returns {JSX.Element}
 */
export default function FeedItemCard({feedItem}) {
    const {t} = useLaravelReactI18n();
    const [totalNumberOfFeedItems, setTotalNumberOfFeedItems] = useContext(TotalNumberOfFeedItemsContext);
    const [internalFeedItem, setInternalFeedItem] = useState(feedItem);
    const [processing, setProcessing] = useState();

    const toggle = () => {
        setProcessing(true);

        axios.put(route('toggle-feed-item', internalFeedItem))
            .then((response) => {
                if (response.data.read_at) {
                    setTotalNumberOfFeedItems(totalNumberOfFeedItems - 1);
                } else {
                    setTotalNumberOfFeedItems(totalNumberOfFeedItems + 1);
                }

                setInternalFeedItem(response.data);
            })
            .finally(() => setProcessing(false));
    };

    return (
        <Card
            key={internalFeedItem.id}
            className={clsx('flex flex-col transition ease-out duration-300', {'opacity-50': internalFeedItem.read_at})}
        >
            {internalFeedItem.has_image ? (
                <Card.Image
                    src={internalFeedItem.image_url}
                    alt={internalFeedItem.title}
                />
            ) : <Card.ImagePlaceholder/>}

            <Card.Body className="grow flex flex-col">
                <div className="grow">
                    <Card.HeaderLink href={internalFeedItem.url}>
                        {internalFeedItem.title}
                    </Card.HeaderLink>

                    <div className="flex items-center py-2">
                        {internalFeedItem.feed.favicon_url ? (
                            <img
                                loading="lazy"
                                src={internalFeedItem.feed.favicon_url}
                                alt={internalFeedItem.feed.name}
                                className="w-4 h-4 mr-2"
                            />
                        ) : <PhotoSolidIcon className="w-4 h-4 mr-2 text-muted"/>}

                        <div className="text-sm text-muted">
                            {internalFeedItem.feed.name}
                        </div>
                    </div>

                    <div className="text-muted overflow-hidden line-clamp-6 xl:line-clamp-3 break-all">
                        {internalFeedItem.description}
                    </div>
                </div>

                <div className="pt-2">
                    <SecondaryButton
                        className="w-full"
                        onClick={toggle}
                        disabled={processing}
                    >
                        {internalFeedItem.read_at ? t('Mark as unread') : t('Mark as read')}
                    </SecondaryButton>
                </div>
            </Card.Body>
        </Card>
    );
}