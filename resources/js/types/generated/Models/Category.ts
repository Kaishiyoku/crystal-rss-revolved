/* this file has been automatically generated */
import User from '@/types/generated/Models/User';
import Feed from '@/types/generated/Models/Feed';

type Category = {
    id: number /** cast attribute */;
    user_id: number;
    name: string;
    user: User;
    feeds: Feed[];
};

export default Category;
