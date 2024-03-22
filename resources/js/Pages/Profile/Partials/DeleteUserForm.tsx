import React, {useEffect, useRef, useState} from 'react';
import InputError from '@/Components/Form/InputError';
import InputLabel from '@/Components/Form/InputLabel';
import {Modal} from '@/Components/Modal/Modal';
import TextInput from '@/Components/Form/TextInput';
import {DangerButton, SecondaryButton} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Card from '@/Components/Card';
import {Form, useActionData} from 'react-router-dom';
import UpdatePasswordValidationErrors from '@/types/UpdatePasswordValidationErrors';

export default function DeleteUserForm() {
    const {t} = useLaravelReactI18n();
    const errors = useActionData() as UpdatePasswordValidationErrors;
    const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);
    const passwordInput = useRef<HTMLInputElement>(null);

    useEffect(() => {
        if (errors) {
            passwordInput.current?.focus();
        }
    }, [errors]);

    const confirmUserDeletion = () => {
        setConfirmingUserDeletion(true);
    };

    const closeModal = () => {
        setConfirmingUserDeletion(false);

        // reset();
    };

    return (
        <Card>
            <div className="max-w-xl sm:p-4">
                <Card.Header
                    title={t('Delete Account')}
                    description={t('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')}
                />

                <Card.Body>
                    <DangerButton onClick={confirmUserDeletion} confirm={false}>
                        {t('Delete Account')}
                    </DangerButton>
                </Card.Body>
            </div>

            <Modal show={confirmingUserDeletion} onClose={closeModal}>
                <Form method="put" action="/profile" className="p-6">
                    <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                        {t('Are you sure you want to delete your account?')}
                    </h2>

                    <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {t('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.')}
                    </p>

                    <div className="mt-6">
                        <InputLabel htmlFor="password" value={t('Password')} className="sr-only"/>

                        <TextInput
                            id="password"
                            type="password"
                            name="password"
                            ref={passwordInput}
                            placeholder={t('Password')}
                            className="block w-full"
                            isFocused
                            required
                        />

                        <InputError message={errors?.password}/>
                    </div>

                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeModal}>
                            {t('Cancel')}
                        </SecondaryButton>

                        <DangerButton type="submit" className="ml-3" confirm={false} name="intent" value="delete">
                            {t('Delete Account')}
                        </DangerButton>
                    </div>
                </Form>
            </Modal>
        </Card>
    );
}
