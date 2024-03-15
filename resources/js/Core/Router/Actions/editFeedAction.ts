import rq from '@/Core/rq';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/Core/Router/Helpers/handleRequestValidationError';
import toast from 'react-hot-toast';

const editFeedAction: ActionFunction = async ({params, request}) => {
    const formData = await request.formData();

    if (!formData.get('is_purgeable')) {
        formData.append('is_purgeable', '0');
    }

    if (formData.get('intent') === 'delete') {
        await rq.delete(`/api/feeds/${params.feedId}`);

        toast('Feed deleted.');

        return null;
    }

    const response = await handleRequestValidationError(() => rq.put(`/api/feeds/${params.feedId}`, {json: Object.fromEntries(formData)}));

    toast('Feed saved.');

    return response;
};

export default editFeedAction;
