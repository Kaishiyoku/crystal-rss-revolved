import React, {useEffect} from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import InputError from '@/Components/Form/InputError';
import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import {Head, useForm} from '@inertiajs/react';
import {PrimaryButton} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function ConfirmPassword() {
    const {t} = useLaravelReactI18n();
    const {data, setData, post, processing, errors, reset} = useForm({password: ''});

    useEffect(() => {
        return () => {
            reset('password');
        };
    }, []);

    const handleOnChange = (event: React.FormEvent<HTMLInputElement>) => {
        const target = event.target as HTMLInputElement;

        // @ts-expect-error we know which fields can occur here
        setData(target.name, target.value);
    };

    const submit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        post(route('password.confirm'));
    };

    return (
        <GuestLayout>
            <Head title={t('Confirm Password')}/>

            <div className="mb-4 text-sm text-gray-600 dark:text-gray-400">
                {t('This is a secure area of the application. Please confirm your password before continuing.')}
            </div>

            <form onSubmit={submit}>
                <div className="mt-4">
                    <InputLabel htmlFor="password" value={t('Password')}/>

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        isFocused={true}
                        onChange={handleOnChange}
                    />

                    <InputError message={errors.password} className="mt-2"/>
                </div>

                <div className="flex items-center justify-end mt-4">
                    <PrimaryButton type="submit" className="ml-4" disabled={processing}>
                        {t('Confirm')}
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
