import Card from '@/Components/Card';
import PhotoSolidIcon from '@/Icons/PhotoSolidIcon';
import {useContext, useState} from 'react';
import clsx from 'clsx';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {SecondaryButton} from '@/Components/Button';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';
import EyeOutlineIcon from '@/Icons/EyeOutlineIcon';
import EyeSlashOutlineIcon from '@/Icons/EyeSlashOutlineIcon';
import formatDateTime from '@/Utils/formatDateTime';
import CalendarDaysSolidIcon from '@/Icons/CalendarDaysSolidIcon';
import PropTypes from 'prop-types';

/**
 * @param {number} hueRotationIndex
 * @param {FeedItem} feedItem
 * @returns {JSX.Element}
 */
export default function FeedItemCard({hueRotationIndex, feedItem}) {
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
            {internalFeedItem.has_image
                ? (
                    <Card.Image
                        src={internalFeedItem.image_url}
                        alt={internalFeedItem.title}
                    />
                )
                : (
                    <Card.ImagePlaceholder
                        className={clsx({
                            'hue-rotate-0': hueRotationIndex === 0,
                            'hue-rotate-30': hueRotationIndex === 1,
                            'hue-rotate-60': hueRotationIndex === 2,
                            'hue-rotate-15': hueRotationIndex === 3,
                            'hue-rotate-180': hueRotationIndex === 4,
                            'hue-rotate-90': hueRotationIndex === 5,
                        })}
                    />
                )}

            <Card.Body className="grow flex flex-col">
                <div className="grow">
                    <Card.HeaderLink href={internalFeedItem.url}>
                        {internalFeedItem.title}
                    </Card.HeaderLink>

                    <div className="text-sm text-muted pt-2 pb-4">
                        <div className="flex items-center pb-1">
                            <CalendarDaysSolidIcon className="w-5 h-5 mr-2"/>
                            {formatDateTime(internalFeedItem.posted_at)}
                        </div>

                        <div className="flex items-center">
                            {internalFeedItem.feed.favicon_url
                                ? (
                                    <img
                                        loading="lazy"
                                        src={internalFeedItem.feed.favicon_url}
                                        alt={internalFeedItem.feed.name}
                                        className="w-5 h-5 rounded mr-2"
                                    />
                                )
                                : <PhotoSolidIcon className="w-5 h-5 mr-2"/>}

                            <div>
                                {internalFeedItem.feed.name}
                            </div>
                        </div>
                    </div>

                    <div className="text-muted overflow-hidden line-clamp-6 xl:line-clamp-3 break-all">
                        {internalFeedItem.description}
                    </div>
                </div>

                <div className="pt-2">
                    <SecondaryButton
                        onClick={toggle}
                        disabled={processing}
                        icon={internalFeedItem.read_at ? EyeSlashOutlineIcon : EyeOutlineIcon}
                        className="w-full"
                    >
                        {internalFeedItem.read_at ? t('Mark as unread') : t('Mark as read')}
                    </SecondaryButton>
                </div>
            </Card.Body>
        </Card>
    );
}
FeedItemCard.propTypes = {
    hueRotationIndex: PropTypes.number.isRequired,
    feedItem: PropTypes.object.isRequired,
};
