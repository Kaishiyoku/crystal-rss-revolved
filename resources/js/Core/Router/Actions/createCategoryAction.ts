import rq from '@/Core/rq';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/Core/Router/Helpers/handleRequestValidationError';
import toast from 'react-hot-toast';

const createCategoryAction: ActionFunction = async ({request}) => {
    const formData = await request.formData();

    const response = await handleRequestValidationError(() => rq.post('/api/categories', {json: Object.fromEntries(formData)}));

    toast('Category saved.');

    return response;
};

export default createCategoryAction;
