import request from '@/V2/request';
import User from '@/types/generated/Models/User';

const layoutLoader = async () => {
    return await request('/api/user').json<User>();
};

export default layoutLoader;
