import Dropdown from '@/Components/Dropdown';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {SecondaryButton} from '@/Components/Button';
import clsx from 'clsx';

export default function FeedFilterDropdown({selectedFeed, feeds, ...props}) {
    const {t} = useLaravelReactI18n();

    if (feeds.length === 0) {
        return null;
    }

    return (
        <div {...props}>
            <Dropdown>
                <Dropdown.Trigger className="inline">
                    <SecondaryButton hasMobileFullSize className="justify-between">
                        <div>
                            {selectedFeed ? selectedFeed.name : t('Filter by feed...')}
                        </div>

                        <svg
                            className="ml-2 -mr-0.5 h-4 w-4"
                            xmlns="http://www.w3.org/2000/svg"
                            viewBox="0 0 20 20"
                            fill="currentColor"
                        >
                            <path
                                fillRule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clipRule="evenodd"
                            />
                        </svg>
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
