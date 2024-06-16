import ValidationErrors from '@/V2/types/ValidationErrors';

type EditCategoryValidationErrors = ValidationErrors & {
    name?: string;
} | null;

export default EditCategoryValidationErrors;
