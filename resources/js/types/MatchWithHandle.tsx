import {UIMatch} from 'react-router-dom';
import RouteHandle from '@/types/RouteHandle';

type MatchWithHandle = UIMatch & {
    handle: RouteHandle;
};

export default MatchWithHandle;
