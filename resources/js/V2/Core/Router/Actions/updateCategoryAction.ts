import request from '@/V2/request';
import {HTTPError} from 'ky';
import ValidationErrors from '@/V2/types/ValidationErrors';
import {ActionFunction} from '@remix-run/router/utils';

const updateCategoryAction: ActionFunction = async ({params, request: req}) => {
    const formData = await req.formData();

    try {
        await request.put(`/api/categories/${params.categoryId}`, {json: Object.fromEntries(formData)});
    } catch (exception) {
        const errorResponse = (exception as HTTPError).response;

        if (errorResponse.status !== 422) {
            throw exception;
        }

        return (await errorResponse.json() as { errors: ValidationErrors; }).errors;
    }

    return null;
};

export default updateCategoryAction;
