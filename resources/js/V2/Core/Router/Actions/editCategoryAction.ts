import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/V2/Core/Router/Helpers/handleRequestValidationError';

const editCategoryAction: ActionFunction = async ({params, request: req}) => {
    const formData = await req.formData();

    if (formData.get('intent') === 'delete') {
        await request.delete(`/api/categories/${params.categoryId}`);

        return null;
    }

    return await handleRequestValidationError(() => request.put(`/api/categories/${params.categoryId}`, {json: Object.fromEntries(formData)}));
};

export default editCategoryAction;
