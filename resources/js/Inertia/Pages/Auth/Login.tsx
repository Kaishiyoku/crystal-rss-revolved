import React, {useEffect} from 'react';
import Checkbox from '@/Components/Form/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import {Head, Link, useForm} from '@inertiajs/react';
import {PrimaryButton} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function Login({status, canResetPassword}: { status: string; canResetPassword: boolean; }) {
    const {t} = useLaravelReactI18n();
    const {data, setData, post, processing, errors, reset} = useForm({
        email: '',
        password: '',
        remember: '',
    });

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const handleOnChange = (event: React.FormEvent<HTMLInputElement>) => {
        const target = event.target as HTMLInputElement;

        // @ts-expect-error we know which fields can occur here
        setData(target.name, target.type === 'checkbox' ? target.checked : target.value);
    };

    const submit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        post(route('login'));
    };

    return (
        <GuestLayout>
            <Head title={t('Log in')}/>

            {status && <div className="mb-4 font-medium text-sm text-green-600">{status}</div>}

            <form onSubmit={submit}>
                <div>
                    <InputLabel htmlFor="email" value={t('Email')}/>

                    <TextInput
                        id="email"
                        type="email"
                        name="email"
                        value={data.email}
                        className="mt-1 block w-full"
                        autoComplete="username"
                        isFocused={true}
                        onChange={handleOnChange}
                    />

                    <InputError message={errors.email} className="mt-2"/>
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password" value={t('Password')}/>

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        autoComplete="current-password"
                        onChange={handleOnChange}
                    />

                    <InputError message={errors.password} className="mt-2"/>
                </div>

                <div className="block mt-4">
                    <label className="flex items-center">
                        <Checkbox name="remember" value={data.remember} onChange={handleOnChange}/>
                        <span className="ml-2 text-sm text-gray-600 dark:text-gray-400">{t('Remember me')}</span>
                    </label>
                </div>

                <div className="flex items-center justify-end mt-4">
                    {canResetPassword && (
                        <Link
                            href={route('password.request')}
                            className="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-gray-800"
                        >
                            {t('Forgot your password?')}
                        </Link>
                    )}

                    <PrimaryButton type="submit" className="ml-4" disabled={processing}>
                        {t('Log in')}
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
