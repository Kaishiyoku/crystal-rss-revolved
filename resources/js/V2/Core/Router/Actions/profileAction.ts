import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/V2/Core/Router/Helpers/handleRequestValidationError';
import {redirect} from 'react-router-dom';

const profileAction: ActionFunction = async ({request: req}) => {
    const formData = await req.formData();

    if (formData.get('intent') === 'update-profile') {
        return await handleRequestValidationError(() => request.patch('/api/profile', {json: Object.fromEntries(formData)}), '/');
    }

    if (formData.get('intent') === 'update-password') {
        return await handleRequestValidationError(() => request.put('/api/password', {json: Object.fromEntries(formData)}), '/');
    }

    if (formData.get('intent') === 'delete') {
        const errors = await handleRequestValidationError(() => request.delete('/api/profile', {json: Object.fromEntries(formData)}));

        if (errors) {
            return errors;
        }

        window.location.href = '/';
    }

    return redirect('/');
};

export default profileAction;
