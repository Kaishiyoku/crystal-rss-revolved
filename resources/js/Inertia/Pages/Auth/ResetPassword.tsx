import React, {useEffect} from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import {Head, useForm} from '@inertiajs/react';
import {PrimaryButton} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function ResetPassword({token, email}: { token: string; email: string; }) {
    const {t} = useLaravelReactI18n();
    const {data, setData, post, processing, errors, reset} = useForm({
        token,
        email,
        password: '',
        password_confirmation: '',
    });

    useEffect(() => {
        return () => {
            reset('password', 'password_confirmation');
        };
    }, []);

    const onHandleChange = (event: React.FormEvent<HTMLInputElement>) => {
        const target = event.target as HTMLInputElement;

        // @ts-expect-error we know which fields can occur here
        setData(target.name, target.value);
    };

    const submit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        post(route('password.store'));
    };

    return (
        <GuestLayout>
            <Head title={t('Reset Password')}/>

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
                        onChange={onHandleChange}
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
                        autoComplete="new-password"
                        isFocused={true}
                        onChange={onHandleChange}
                    />

                    <InputError message={errors.password} className="mt-2"/>
                </div>

                <div className="mt-4">
                    <InputLabel htmlFor="password_confirmation" value={t('Confirm Password')}/>

                    <TextInput
                        type="password"
                        name="password_confirmation"
                        value={data.password_confirmation}
                        className="mt-1 block w-full"
                        autoComplete="new-password"
                        onChange={onHandleChange}
                    />

                    <InputError message={errors.password_confirmation} className="mt-2"/>
                </div>

                <div className="flex items-center justify-end mt-4">
                    <PrimaryButton type="submit" className="ml-4" disabled={processing}>
                        {t('Reset Password')}
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
