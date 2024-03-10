import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/V2/Core/Router/Helpers/handleRequestValidationError';

const createCategoryAction: ActionFunction = async ({request: req}) => {
    const formData = await req.formData();

    return await handleRequestValidationError(() => request.post('/api/categories', {json: Object.fromEntries(formData)}));
};

export default createCategoryAction;
