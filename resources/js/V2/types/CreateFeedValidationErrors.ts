import ValidationErrors from '@/V2/types/ValidationErrors';

type CreateFeedValidationErrors = ValidationErrors & {
    name?: string;
    category_id?: string;
    language?: string;
    feed_url?: string;
    site_url?: string;
    favicon_url?: string;
} | null;

export default CreateFeedValidationErrors;
