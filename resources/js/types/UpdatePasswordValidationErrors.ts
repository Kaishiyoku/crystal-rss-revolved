import ValidationErrors from '@/types/ValidationErrors';

type UpdatePasswordValidationErrors = ValidationErrors & {
    current_password?: string;
    password?: string;
    password_confirmation?: string;
} | null;

export default UpdatePasswordValidationErrors;
