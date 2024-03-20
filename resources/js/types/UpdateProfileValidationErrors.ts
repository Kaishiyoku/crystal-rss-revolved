import ValidationErrors from '@/types/ValidationErrors';

type UpdateProfileValidationErrors = ValidationErrors & {
    name?: string;
    email?: string;
} | null;

export default UpdateProfileValidationErrors;
