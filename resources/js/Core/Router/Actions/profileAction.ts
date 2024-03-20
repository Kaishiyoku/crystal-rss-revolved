import rq from '@/Core/rq';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/Core/Router/Helpers/handleRequestValidationError';
import {redirect} from 'react-router-dom';
import toast from 'react-hot-toast';

const profileAction: ActionFunction = async ({request}) => {
    const formData = await request.formData();

    if (formData.get('intent') === 'update-profile') {
        const response = await handleRequestValidationError(() => rq.patch('/api/profile', {json: Object.fromEntries(formData)}), '/');

        toast('Profile saved.');

        return response;
    }

    if (formData.get('intent') === 'update-password') {
        const response = await handleRequestValidationError(() => rq.put('/api/password', {json: Object.fromEntries(formData)}), '/');

        toast('Password updated.');

        return response;
    }

    if (formData.get('intent') === 'delete') {
        const errors = await handleRequestValidationError(() => rq.delete('/api/profile', {json: Object.fromEntries(formData)}));

        if (errors) {
            return errors;
        }

        window.location.href = '/';
    }

    return redirect('/');
};

export default profileAction;
