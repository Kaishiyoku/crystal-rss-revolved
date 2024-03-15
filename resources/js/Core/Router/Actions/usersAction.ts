import rq from '@/Core/rq';
import {ActionFunction} from '@remix-run/router/utils';
import {redirect} from 'react-router-dom';
import toast from 'react-hot-toast';

const usersAction: ActionFunction = async ({request}) => {
    const formData = await request.formData();

    if (formData.get('intent') === 'delete') {
        await rq.delete(`/api/admin/users/${formData.get('userId')}`);

        toast('User deleted.');
    }

    return redirect('/admin/users');
};

export default usersAction;
