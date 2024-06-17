import React, {useRef} from 'react';
import {useForm} from '@inertiajs/react';
import {Transition} from '@headlessui/react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import InputError from '@/Components/Form/InputError';
import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import {PrimaryButton} from '@/Components/Button';
import Card from '@/Components/Card';

export default function UpdatePasswordForm() {
    const {t} = useLaravelReactI18n();
    const passwordInput = useRef<HTMLInputElement>();
    const currentPasswordInput = useRef<HTMLInputElement>();

    const {data, setData, errors, put, reset, processing, recentlySuccessful} = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const updatePassword = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: () => {
                if (errors.password) {
                    reset('password', 'password_confirmation');
                    passwordInput.current?.focus();
                }

                if (errors.current_password) {
                    reset('current_password');
                    currentPasswordInput.current?.focus();
                }
            },
        });
    };

    return (
        <Card>
            <div className="max-w-xl sm:p-4">
                <Card.Header
                    title={t('Update Password')}
                    description={t('Ensure your account is using a long, random password to stay secure.')}
                />

                <Card.Body>
                    <form onSubmit={updatePassword} className="space-y-6">
                        <div>
                            <InputLabel htmlFor="current_password" value={t('Current Password')}/>

                            <TextInput
                                id="current_password"
                                ref={currentPasswordInput}
                                value={data.current_password}
                                onChange={(e) => setData('current_password', e.target.value)}
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="current-password"
                            />

                            <InputError message={errors.current_password} className="mt-2"/>
                        </div>

                        <div>
                            <InputLabel htmlFor="password" value={t('New Password')}/>

                            <TextInput
                                id="password"
                                ref={passwordInput}
                                value={data.password}
                                onChange={(e) => setData('password', e.target.value)}
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />

                            <InputError message={errors.password} className="mt-2"/>
                        </div>

                        <div>
                            <InputLabel htmlFor="password_confirmation" value={t('Confirm Password')}/>

                            <TextInput
                                id="password_confirmation"
                                value={data.password_confirmation}
                                onChange={(e) => setData('password_confirmation', e.target.value)}
                                type="password"
                                className="mt-1 block w-full"
                                autoComplete="new-password"
                            />

                            <InputError message={errors.password_confirmation} className="mt-2"/>
                        </div>

                        <div className="flex items-center gap-4">
                            <PrimaryButton type="submit" disabled={processing}>{t('Save')}</PrimaryButton>

                            <Transition
                                show={recentlySuccessful}
                                enterFrom="opacity-0"
                                leaveTo="opacity-0"
                                className="transition ease-in-out"
                            >
                                <p className="text-sm text-gray-600 dark:text-gray-400">{t('Saved.')}</p>
                            </Transition>
                        </div>
                    </form>
                </Card.Body>
            </div>
        </Card>
    );
}
