import ValidationErrors from '@/V2/types/ValidationErrors';

type UpdatePasswordValidationErrors = ValidationErrors & {
    current_password?: string;
    password?: string;
    password_confirmation?: string;
} | null;

export default UpdatePasswordValidationErrors;
