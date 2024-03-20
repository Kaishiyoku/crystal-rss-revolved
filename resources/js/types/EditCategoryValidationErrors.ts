import ValidationErrors from '@/types/ValidationErrors';

type EditCategoryValidationErrors = ValidationErrors & {
    name?: string;
} | null;

export default EditCategoryValidationErrors;
