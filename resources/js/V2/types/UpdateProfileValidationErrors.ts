import ValidationErrors from '@/V2/types/ValidationErrors';

type UpdateProfileValidationErrors = ValidationErrors & {
    name?: string;
    email?: string;
} | null;

export default UpdateProfileValidationErrors;
