import ValidationErrors from '@/types/ValidationErrors';

type EditFeedValidationErrors = ValidationErrors & {
    name?: string;
    category_id?: string;
    language?: string;
    feed_url?: string;
    site_url?: string;
    favicon_url?: string;
} | null;

export default EditFeedValidationErrors;
