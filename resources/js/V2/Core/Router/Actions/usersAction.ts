import request from '@/V2/request';
import {ActionFunction} from '@remix-run/router/utils';
import {redirect} from 'react-router-dom';
import toast from 'react-hot-toast';

const usersAction: ActionFunction = async ({request: req}) => {
    const formData = await req.formData();

    if (formData.get('intent') === 'delete') {
        await request.delete(`/api/admin/users/${formData.get('userId')}`);

        toast('User deleted.');
    }

    return redirect('/admin/users');
};

export default usersAction;
