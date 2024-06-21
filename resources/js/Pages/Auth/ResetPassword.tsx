import React, {useEffect} from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import {Head, useForm} from '@inertiajs/react';
import {Button} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {ErrorMessage, Field, FieldGroup, Label} from '@/Components/Fieldset';
import {Input} from '@/Components/Form/Input';

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
                <FieldGroup>
                    <Field>
                        <Label htmlFor="email">
                            {t('Email')}
                        </Label>
                        <Input
                            id="email"
                            type="email"
                            name="email"
                            value={data.email}
                            className="mt-1 block w-full"
                            autoComplete="username"
                            onChange={onHandleChange}
                        />
                        <ErrorMessage>
                            {errors.email}
                        </ErrorMessage>
                    </Field>

                    <Field>
                        <Label htmlFor="password">
                            {t('Password')}
                        </Label>
                        <Input
                            id="password"
                            type="password"
                            name="password"
                            value={data.password}
                            className="mt-1 block w-full"
                            autoComplete="new-password"
                            onChange={onHandleChange}
                            autoFocus
                        />
                        <ErrorMessage>
                            {errors.password}
                        </ErrorMessage>
                    </Field>

                    <Field>
                        <Label htmlFor="password_confirmation">
                            {t('Confirm Password')}
                        </Label>
                        <Input
                            type="password"
                            name="password_confirmation"
                            value={data.password_confirmation}
                            className="mt-1 block w-full"
                            autoComplete="new-password"
                            onChange={onHandleChange}
                        />
                        <ErrorMessage>
                            {errors.password_confirmation}
                        </ErrorMessage>
                    </Field>

                    <div className="flex items-center justify-end mt-4">
                        <Button type="submit" className="ml-4" disabled={processing}>
                            {t('Reset Password')}
                        </Button>
                    </div>
                </FieldGroup>
            </form>
        </GuestLayout>
    );
}
