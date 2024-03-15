import {HTTPError, ResponsePromise} from 'ky';
import ValidationErrors from '@/V2/types/ValidationErrors';
import {redirect} from 'react-router-dom';

export default async function handleRequestValidationError(requestFn: () => ResponsePromise, to?: string) {
    try {
        await requestFn();
    } catch (exception) {
        const errorResponse = (exception as HTTPError).response;

        if (errorResponse.status !== 422) {
            throw exception;
        }

        return (await errorResponse.json() as { errors: ValidationErrors; }).errors;
    }

    if (to) {
        return redirect(to);
    }

    return null;
}
