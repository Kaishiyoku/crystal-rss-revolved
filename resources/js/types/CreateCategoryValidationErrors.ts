import ValidationErrors from '@/types/ValidationErrors';

type CreateCategoryValidationErrors = ValidationErrors & {
    name?: string;
} | null;

export default CreateCategoryValidationErrors;
