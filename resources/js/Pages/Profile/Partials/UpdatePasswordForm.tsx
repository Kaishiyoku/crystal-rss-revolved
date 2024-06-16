import React, {useEffect, useRef} from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import InputError from '@/Components/Form/InputError';
import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import {PrimaryButton} from '@/Components/Button';
import Card from '@/Components/Card';
import {Form, useActionData} from 'react-router-dom';
import UpdatePasswordValidationErrors from '@/V2/types/UpdatePasswordValidationErrors';

export default function UpdatePasswordForm() {
    const {t} = useLaravelReactI18n();
    const errors = useActionData() as UpdatePasswordValidationErrors;
    const currentPasswordInput = useRef<HTMLInputElement>(null);
    const passwordInput = useRef<HTMLInputElement>(null);
    const passwordConfirmationInput = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (errors && currentPasswordInput.current && passwordInput.current && passwordConfirmationInput.current) {
            currentPasswordInput.current.value = '';
            passwordInput.current.value = '';
            passwordConfirmationInput.current.value = '';
        }

        if (errors?.password) {
            passwordInput.current?.focus();
        }

        if (errors?.current_password) {
            currentPasswordInput.current?.focus();
        }
    }, [errors]);

    return (
        <Card>
            <div className="max-w-xl sm:p-4">
                <Card.Header
                    title={t('Update Password')}
                    description={t('Ensure your account is using a long, random password to stay secure.')}
                />

                <Card.Body>
                    <Form method="put" action="/profile" className="space-y-4">
                        <div>
                            <InputLabel htmlFor="current_password" value={t('Current Password')}/>

                            <TextInput
                                id="current_password"
                                name="current_password"
                                ref={currentPasswordInput}
                                type="password"
                                className="block w-full"
                                autoComplete="current-password"
                            />

                            <InputError message={errors?.current_password}/>
                        </div>

                        <div>
                            <InputLabel htmlFor="password" value={t('New Password')}/>

                            <TextInput
                                id="password"
                                name="password"
                                ref={passwordInput}
                                type="password"
                                className="block w-full"
                                autoComplete="new-password"
                            />

                            <InputError message={errors?.password}/>
                        </div>

                        <div>
                            <InputLabel htmlFor="password_confirmation" value={t('Confirm Password')}/>

                            <TextInput
                                id="password_confirmation"
                                name="password_confirmation"
                                ref={passwordConfirmationInput}
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />

                            <InputError message={errors?.password_confirmation}/>
                        </div>

                        <PrimaryButton type="submit" name="intent" value="update-password">
                            {t('Save')}
                        </PrimaryButton>
                    </Form>
                </Card.Body>
            </div>
        </Card>
    );
}
