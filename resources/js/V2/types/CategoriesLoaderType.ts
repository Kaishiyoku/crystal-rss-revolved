import CategoryWithFeedsCount from '@/types/generated/Models/CategoryWithFeedsCount';

type CategoriesLoaderType = {
    categories: CategoryWithFeedsCount[];
    canCreate: boolean;
}
export default CategoriesLoaderType;
