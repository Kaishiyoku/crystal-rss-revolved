import {SelectNumberOption} from '@/types/SelectOption';
import Feed from '@/types/generated/Models/Feed';

type EditFeedLoaderType = {
    categories: SelectNumberOption[];
    feed: Feed;
    canDelete: boolean;
}

export default EditFeedLoaderType;
