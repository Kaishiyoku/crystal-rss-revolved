import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import handleRequestValidationError from '@/V2/Core/Router/Helpers/handleRequestValidationError';
import {redirect} from 'react-router-dom';

const profileAction: ActionFunction = async ({request: req}) => {
    const formData = await req.formData();

    console.log(Object.fromEntries(formData));

    // if (formData.get('intent') === 'delete') {
    //     await request.delete(`/api/feeds/${params.feedId}`);
    //
    //     return null;
    // }

    if (formData.get('intent') === 'update-profile') {
        return await handleRequestValidationError(() => request.patch('/api/profile', {json: Object.fromEntries(formData)}));
    }

    return redirect('/');
};

export default profileAction;
