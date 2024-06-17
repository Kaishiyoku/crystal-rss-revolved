import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import TextInput from '@/Components/Form/TextInput';
import {Head, useForm} from '@inertiajs/react';
import {PrimaryButton} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import React from 'react';

export default function ForgotPassword({status}: { status: string; }) {
    const {t} = useLaravelReactI18n();
    const {data, setData, post, processing, errors} = useForm({email: ''});

    const onHandleChange = (event: React.FormEvent<HTMLInputElement>) => {
        const target = event.target as HTMLInputElement;

        // @ts-expect-error we know which fields can occur here
        setData(target.name, target.value);
    };

    const submit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        post(route('password.email'));
    };

    return (
        <GuestLayout>
            <Head title={t('Forgot Password')}/>

            <div className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {t('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.')}
            </div>

            {status && <div className="mb-4 font-medium text-sm text-green-600 dark:text-green-400">{status}</div>}

            <form onSubmit={submit}>
                <TextInput
                    id="email"
                    type="email"
                    name="email"
                    value={data.email}
                    className="mt-1 block w-full"
                    isFocused={true}
                    onChange={onHandleChange}
                />

                <InputError message={errors.email} className="mt-2"/>

                <div className="flex items-center justify-end mt-4">
                    <PrimaryButton type="submit" disabled={processing}>
                        {t('Email Password Reset Link')}
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
