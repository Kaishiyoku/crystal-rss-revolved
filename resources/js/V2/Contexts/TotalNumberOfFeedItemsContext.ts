import {createContext} from 'react';
import noop from '@/Utils/noop';
import TotalNumberOfFeedItemsContextType from '@/V2/types/TotalNumberOfFeedItemsContextType';

const TotalNumberOfFeedItemsContext = createContext<TotalNumberOfFeedItemsContextType>({
    totalNumberOfFeedItems: 0,
    setTotalNumberOfFeedItems: noop,
});

export default TotalNumberOfFeedItemsContext;
