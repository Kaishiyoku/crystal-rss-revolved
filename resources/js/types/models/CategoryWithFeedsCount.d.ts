import {Category} from '@/types/generated/models';

type CategoryWithFeedsCount = Category & {
    feeds_count: number;
};

export default CategoryWithFeedsCount;
