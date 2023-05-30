import Dropdown from '@/Components/Dropdown';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {SecondaryButton} from '@/Components/Button';
import FunnelOutlineIcon from '@/Icons/FunnelOutlineIcon';
import DropdownArrowIcon from '@/Icons/DropdownArrowIcon';

export default function FeedFilterDropdown({selectedFeed, feeds, ...props}) {
    const {t} = useLaravelReactI18n();

    if (feeds.length === 0) {
        return null;
    }

    return (
        <div {...props}>
            <Dropdown>
                <Dropdown.Trigger className="inline">
                    <SecondaryButton
                        className="justify-between"
                        icon={FunnelOutlineIcon}
                        hasMobileFullSize
                    >
                        <div>
                            {selectedFeed ? `${selectedFeed.name} (${selectedFeed.feed_items_count})` : t('Filter by feed...')}
                        </div>

                        <DropdownArrowIcon/>
                    </SecondaryButton>
                </Dropdown.Trigger>

                <Dropdown.Content
                    align="left"
                    width={96}
                >
                    {selectedFeed && (
                        <>
                            <Dropdown.Link href={route('dashboard')}>
                                {t('All feeds')}
                            </Dropdown.Link>

                            <Dropdown.Spacer/>
                        </>
                    )}

                    {feeds.map((feed) => (
                        <Dropdown.Link
                            key={feed.id}
                            href={`${route('dashboard')}?feed_id=${feed.id}`}
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
