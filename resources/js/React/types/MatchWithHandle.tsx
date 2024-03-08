import {UIMatch} from 'react-router-dom';

type MatchWithHandle = UIMatch & {
    handle: {
        headline: string;
        title: string;
    };
};

export default MatchWithHandle;
