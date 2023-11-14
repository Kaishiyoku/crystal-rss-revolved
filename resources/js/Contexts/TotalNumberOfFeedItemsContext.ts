import {createContext} from 'react';
import noop from '@/Utils/noop';

type TotalNumberOfFeedItemsContextType = {
    totalNumberOfFeedItems: number;
    setTotalNumberOfFeedItems: (value: number) => void;
}

const TotalNumberOfFeedItemsContext = createContext<TotalNumberOfFeedItemsContextType>({
    totalNumberOfFeedItems: 0,
    setTotalNumberOfFeedItems: noop,
});

export default TotalNumberOfFeedItemsContext;
