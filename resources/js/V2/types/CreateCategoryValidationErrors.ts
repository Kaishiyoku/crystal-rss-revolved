import ValidationErrors from '@/V2/types/ValidationErrors';

type CreateCategoryValidationErrors = ValidationErrors & {
    name?: string;
} | null;

export default CreateCategoryValidationErrors;
