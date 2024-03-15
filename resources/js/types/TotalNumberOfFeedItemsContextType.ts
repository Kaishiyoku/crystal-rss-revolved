import {Dispatch, SetStateAction} from 'react';

type TotalNumberOfFeedItemsContextType = {
    totalNumberOfFeedItems: number;
    setTotalNumberOfFeedItems: Dispatch<SetStateAction<number>>;
};

export default TotalNumberOfFeedItemsContextType;
