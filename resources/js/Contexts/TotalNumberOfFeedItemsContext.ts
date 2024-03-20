import {createContext} from 'react';
import TotalNumberOfFeedItemsContextType from '@/types/TotalNumberOfFeedItemsContextType';
import noop from '@/Utils/noop';

const TotalNumberOfFeedItemsContext = createContext<TotalNumberOfFeedItemsContextType>({
    totalNumberOfFeedItems: 0,
    setTotalNumberOfFeedItems: noop,
});

export default TotalNumberOfFeedItemsContext;
