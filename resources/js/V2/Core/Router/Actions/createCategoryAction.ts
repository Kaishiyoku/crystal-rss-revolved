import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/V2/Core/Router/Helpers/handleRequestValidationError';
import toast from 'react-hot-toast';

const createCategoryAction: ActionFunction = async ({request: req}) => {
    const formData = await req.formData();

    const response = await handleRequestValidationError(() => request.post('/api/categories', {json: Object.fromEntries(formData)}));

    toast('Category saved.');

    return response;
};

export default createCategoryAction;
