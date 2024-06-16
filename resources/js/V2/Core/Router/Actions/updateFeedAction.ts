import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/V2/Core/Router/Helpers/handleRequestValidationError';

const updateFeedAction: ActionFunction = async ({params, request: req}) => {
    const formData = await req.formData();

    if (!formData.get('is_purgeable')) {
        formData.append('is_purgeable', '0');
    }

    if (formData.get('intent') === 'delete') {
        await request.delete(`/api/feeds/${params.feedId}`);

        return null;
    }

    return await handleRequestValidationError(() => request.put(`/api/feeds/${params.feedId}`, {json: Object.fromEntries(formData)}));
};

export default updateFeedAction;
