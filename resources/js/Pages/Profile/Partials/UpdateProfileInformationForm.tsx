import {useLaravelReactI18n} from 'laravel-react-i18n';
import InputError from '@/Components/Form/InputError';
import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import {HeadlessButton, PrimaryButton} from '@/Components/Button';
import React from 'react';
import Card from '@/Components/Card';
import User from '@/types/generated/Models/User';
import {Form, useActionData} from 'react-router-dom';
import UpdateProfileValidationErrors from '@/V2/types/UpdateProfileValidationErrors';

export default function UpdateProfileInformation({mustVerifyEmail, status, user}: { mustVerifyEmail: boolean; status: string; user: User; }) {
    const {t} = useLaravelReactI18n();
    const errors = useActionData() as UpdateProfileValidationErrors;

    const handleSendVerification = () => {

    };

    return (
        <Card>
            <div className="max-w-xl sm:p-4">
                <Card.Header
                    title={t('Profile Information')}
                    description={t('Update your account\'s profile information and email address.')}
                />

                <Card.Body>
                    <Form method="patch" action="/profile" className="space-y-4">
                        <div>
                            <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>
                            <TextInput
                                id="name"
                                name="name"
                                defaultValue={user.name}
                                className="block w-full"
                                autoComplete="name"
                                required
                                isFocused
                            />
                            <InputError message={errors?.name}/>
                        </div>

                        <div>
                            <InputLabel htmlFor="email" value={t('validation.attributes.email')} required/>
                            <TextInput
                                id="email"
                                name="email"
                                type="email"
                                defaultValue={user.email}
                                className="block w-full"
                                autoComplete="email"
                                required
                                isFocused
                            />
                            <InputError message={errors?.email}/>
                        </div>

                        {mustVerifyEmail && user.email_verified_at === null && (
                            <div>
                                <p className="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                    {t('Your email address is unverified.')}
                                    <HeadlessButton
                                        className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-gray-800"
                                        onClick={handleSendVerification}
                                    >
                                        {t('Click here to re-send the verification email.')}
                                    </HeadlessButton>
                                </p>

                                {status === 'verification-link-sent' && (
                                    <div className="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                        {t('A new verification link has been sent to your email address.')}
                                    </div>
                                )}
                            </div>
                        )}

                        <PrimaryButton type="submit" name="intent" value="update-profile">
                            {t('Save')}
                        </PrimaryButton>
                    </Form>
                </Card.Body>
            </div>
        </Card>
    );
}
