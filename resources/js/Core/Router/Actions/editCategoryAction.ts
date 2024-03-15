import rq from '@/Core/rq';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/Core/Router/Helpers/handleRequestValidationError';
import toast from 'react-hot-toast';

const editCategoryAction: ActionFunction = async ({params, request}) => {
    const formData = await request.formData();

    if (formData.get('intent') === 'delete') {
        await rq.delete(`/api/categories/${params.categoryId}`);

        toast('Category deleted.');

        return null;
    }

    const response = await handleRequestValidationError(() => rq.put(`/api/categories/${params.categoryId}`, {json: Object.fromEntries(formData)}));

    toast('Category saved.');

    return response;
};

export default editCategoryAction;
