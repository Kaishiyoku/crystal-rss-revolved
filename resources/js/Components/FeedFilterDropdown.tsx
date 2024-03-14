import Dropdown from '@/Components/Dropdown';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {TertiaryButton} from '@/Components/Button';
import FunnelOutlineIcon from '@/Icons/FunnelOutlineIcon';
import DropdownArrowIcon from '@/Icons/DropdownArrowIcon';
import {OtherProps} from '@/types';
import ShortFeedWithFeedItemsCount from '@/types/generated/Models/ShortFeedWithFeedItemsCount';
import {length} from 'ramda';

export default function FeedFilterDropdown({selectedFeed, feeds, ...props}: { selectedFeed: ShortFeedWithFeedItemsCount | null; feeds: ShortFeedWithFeedItemsCount[]; props?: OtherProps; }) {
    const {t} = useLaravelReactI18n();

    if (length(feeds) === 0) {
        return null;
    }

    const getFeedFilterUrl = (id: number) => {
        return `/?${new URLSearchParams({feed_id: id.toString()}).toString()}`;
    };

    return (
        <div {...props}>
            <Dropdown>
                <Dropdown.Trigger className="inline">
                    <TertiaryButton
                        className="justify-between"
                        icon={FunnelOutlineIcon}
                        hasMobileFullSize
                    >
                        <div className="max-w-[200px] truncate" title={selectedFeed ? `${selectedFeed.name} (${selectedFeed.feed_items_count})` : t('Filter by feed...')}>
                            {selectedFeed ? `${selectedFeed.name} (${selectedFeed.feed_items_count})` : t('Filter by feed...')}
                        </div>

                        <DropdownArrowIcon/>
                    </TertiaryButton>
                </Dropdown.Trigger>

                <Dropdown.Content
                    align="left"
                    width={96}
                >
                    {selectedFeed && (
                        <>
                            <Dropdown.Link to="/">
                                {t('All feeds')}
                            </Dropdown.Link>

                            <Dropdown.Spacer/>
                        </>
                    )}

                    {feeds.map((feed) => (
                        <Dropdown.Link
                            key={feed.id}

                            to={getFeedFilterUrl(feed.id)}
                            active={selectedFeed?.id === feed.id}
                        >
                            {feed.name} ({feed.feed_items_count})
                        </Dropdown.Link>
                    ))}
                </Dropdown.Content>
            </Dropdown>
        </div>
    );
}
