import React, {useRef, useState} from 'react';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import {Modal} from '@/Components/Modal/Modal';
import TextInput from '@/Components/TextInput';
import {useForm} from '@inertiajs/react';
import {DangerButton, SecondaryButton} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function DeleteUserForm({className = ''}: { className?: string; }) {
    const {t} = useLaravelReactI18n();
    const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);
    const passwordInput = useRef<HTMLInputElement>();

    const {
        data,
        setData,
        delete: destroy,
        processing,
        reset,
        errors,
    } = useForm({password: ''});

    const confirmUserDeletion = () => {
        setConfirmingUserDeletion(true);
    };

    const deleteUser = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        destroy(route('profile.destroy'), {
            preserveScroll: true,
            onSuccess: () => closeModal(),
            onError: () => passwordInput.current?.focus(),
            onFinish: () => reset(),
        });
    };

    const closeModal = () => {
        setConfirmingUserDeletion(false);

        reset();
    };

    return (
        <section className={`space-y-6 ${className}`}>
            <header>
                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">{t('Delete Account')}</h2>

                <p className="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {t('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.')}
                </p>
            </header>

            <DangerButton onClick={confirmUserDeletion} confirm={false}>{t('Delete Account')}</DangerButton>

            <Modal show={confirmingUserDeletion} onClose={closeModal}>
                <form onSubmit={deleteUser} className="p-6">
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
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            className="mt-1 block w-full"
                            isFocused
                            placeholder={t('Password')}
                        />

                        <InputError message={errors.password} className="mt-2"/>
                    </div>

                    <div className="mt-6 flex justify-end">
                        <SecondaryButton onClick={closeModal}>{t('Cancel')}</SecondaryButton>

                        <DangerButton type="submit" className="ml-3" confirm={false} disabled={processing}>
                            {t('Delete Account')}
                        </DangerButton>
                    </div>
                </form>
            </Modal>
        </section>
    );
}