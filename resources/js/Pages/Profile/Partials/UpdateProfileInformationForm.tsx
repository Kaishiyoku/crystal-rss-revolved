import {Link, useForm, usePage} from '@inertiajs/react';
import {Transition} from '@headlessui/react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import {PrimaryButton} from '@/Components/Button';
import React from 'react';
import User from '@/types/Models/User';
import Card from '@/Components/Card';

export default function UpdateProfileInformation({mustVerifyEmail, status}: { mustVerifyEmail: boolean; status: string; }) {
    const {t} = useLaravelReactI18n();
    // @ts-expect-error we know that the page props include the authenticated user
    const user = usePage().props.auth.user as User;

    const {data, setData, patch, errors, processing, recentlySuccessful} = useForm({
        name: user.name,
        email: user.email,
    });

    const submit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        patch(route('profile.update'));
    };

    return (
        <Card>
            <div className="max-w-xl sm:p-4">
                <Card.Header
                    title={t('Profile Information')}
                    description={t('Update your account\'s profile information and email address.')}
                />

                <Card.Body>
                    <form onSubmit={submit} className="space-y-6">
                        <div>
                            <InputLabel htmlFor="name" value="Name"/>

                            <TextInput
                                id="name"
                                className="mt-1 block w-full"
                                value={data.name}
                                onChange={(e) => setData('name', e.target.value)}
                                required
                                isFocused
                                autoComplete="name"
                            />

                            <InputError className="mt-2" message={errors.name}/>
                        </div>

                        <div>
                            <InputLabel htmlFor="email" value="Email"/>

                            <TextInput
                                id="email"
                                type="email"
                                className="mt-1 block w-full"
                                value={data.email}
                                onChange={(e) => setData('email', e.target.value)}
                                required
                                autoComplete="username"
                            />

                            <InputError className="mt-2" message={errors.email}/>
                        </div>

                        {mustVerifyEmail && user.email_verified_at === null && (
                            <div>
                                <p className="text-sm mt-2 text-gray-800 dark:text-gray-200">
                                    Your email address is unverified.
                                    <Link
                                        href={route('verification.send')}
                                        method="post"
                                        as="button"
                                        className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-gray-800"
                                    >
                                        Click here to re-send the verification email.
                                    </Link>
                                </p>

                                {status === 'verification-link-sent' && (
                                    <div className="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
                                        A new verification link has been sent to your email address.
                                    </div>
                                )}
                            </div>
                        )}

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
