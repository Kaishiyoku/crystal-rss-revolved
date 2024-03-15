import {ReactElement, useState} from 'react';
import TotalNumberOfFeedItemsContext from '@/Contexts/TotalNumberOfFeedItemsContext';

export default function TotalNumberOfFeedItemsProvider({children}: { children: ReactElement; }) {
    const [totalNumberOfFeedItems, setTotalNumberOfFeedItems] = useState(0);

    return (
        <TotalNumberOfFeedItemsContext.Provider value={{totalNumberOfFeedItems, setTotalNumberOfFeedItems}}>
            {children}
        </TotalNumberOfFeedItemsContext.Provider>
    );
}
