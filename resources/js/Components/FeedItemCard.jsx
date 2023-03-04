import Card from '@/Components/Card';
import PhotoSolidIcon from '@/Icons/PhotoSolidIcon';
import SecondaryButton from '@/Components/SecondaryButton';

export default function FeedItemCard({feedItem}) {
    return (
        <Card
            key={feedItem.id}
            className="flex flex-col"
        >
            {feedItem.has_image && (
                <Card.Image
                    src={feedItem.image_url}
                    alt={feedItem.title}
                />
            )}

            {!feedItem.has_image && (
                <Card.ImagePlaceholder/>
            )}

            <Card.Body className="grow flex flex-col">
                <div className="grow">
                    <Card.HeaderLink href={feedItem.url}>
                        {feedItem.title}
                    </Card.HeaderLink>

                    <div className="flex items-center py-2">
                        {feedItem.feed.favicon_url ? (
                            <img
                                loading="lazy"
                                src={feedItem.feed.favicon_url}
                                alt={feedItem.feed.name}
                                className="w-4 h-4 mr-2"
                            />
                        ) : <PhotoSolidIcon className="w-4 h-4 mr-2 text-muted"/>}

                        <div className="text-sm text-muted">
                            {feedItem.feed.name}
                        </div>
                    </div>

                    <div className="text-muted overflow-hidden line-clamp-6 xl:line-clamp-3 break-all">
                        {feedItem.description}
                    </div>
                </div>

                <div className="pt-2">
                    <SecondaryButton className="w-full">
                        Mark as read
                    </SecondaryButton>
                </div>
            </Card.Body>
        </Card>
    );
}
