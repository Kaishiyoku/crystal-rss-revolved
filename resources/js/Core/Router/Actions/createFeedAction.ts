import rq from '@/Core/rq';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/Core/Router/Helpers/handleRequestValidationError';
import toast from 'react-hot-toast';

const createFeedAction: ActionFunction = async ({request}) => {
    const formData = await request.formData();

    if (!formData.get('is_purgeable')) {
        formData.append('is_purgeable', '0');
    }

    const response = await handleRequestValidationError(() => rq.post('/api/feeds', {json: Object.fromEntries(formData)}));

    toast('Feed saved.');

    return response;
};

export default createFeedAction;
