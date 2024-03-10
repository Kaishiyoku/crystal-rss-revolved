import {UIMatch} from 'react-router-dom';
import RouteHandle from '@/V2/types/RouteHandle';

type MatchWithHandle = UIMatch & {
    handle: RouteHandle;
};

export default MatchWithHandle;
