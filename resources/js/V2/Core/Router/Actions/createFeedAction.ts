import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/V2/Core/Router/Helpers/handleRequestValidationError';
import toast from 'react-hot-toast';

const createFeedAction: ActionFunction = async ({request: req}) => {
    const formData = await req.formData();

    if (!formData.get('is_purgeable')) {
        formData.append('is_purgeable', '0');
    }

    const response = await handleRequestValidationError(() => request.post('/api/feeds', {json: Object.fromEntries(formData)}));

    toast('Feed saved.');

    return response;
};

export default createFeedAction;
