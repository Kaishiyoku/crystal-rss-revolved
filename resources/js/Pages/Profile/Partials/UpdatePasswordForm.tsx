import {FormEventHandler, useRef} from 'react';
import {useForm} from '@inertiajs/react';
import {Transition} from '@headlessui/react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Button} from '@/Components/Button';
import {Input} from '@/Components/Form/Input';
import {ErrorMessage, Field, FieldGroup, Label} from '@/Components/Fieldset';

export default function UpdatePasswordForm({className = ''}: { className?: string; }) {
    const {t} = useLaravelReactI18n();

    const passwordInput = useRef<HTMLInputElement>(null);
    const currentPasswordInput = useRef<HTMLInputElement>(null);

    const {data, setData, errors, put, reset, processing, recentlySuccessful} = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    });

    const updatePassword: FormEventHandler = (e) => {
        e.preventDefault();

        put(route('password.update'), {
            preserveScroll: true,
            onSuccess: () => reset(),
            onError: (errors) => {
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
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-zinc-900 dark:text-zinc-100">{t('Update Password')}</h2>

                <p className="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
                    {t('Ensure your account is using a long, random password to stay secure.')}
                </p>
            </header>

            <form onSubmit={updatePassword}>
                <FieldGroup>
                    <Field>
                        <Label htmlFor="current_password">
                            {t('Current Password')}
                        </Label>
                        <Input
                            id="current_password"
                            ref={currentPasswordInput}
                            value={data.current_password}
                            onChange={(e) => setData('current_password', e.target.value)}
                            type="password"
                            autoComplete="current-password"
                            invalid={!!errors.current_password}
                        />
                        <ErrorMessage>
                            {errors.current_password}
                        </ErrorMessage>
                    </Field>

                    <Field>
                        <Label htmlFor="password">
                            {t('New Password')}
                        </Label>
                        <Input
                            id="password"
                            ref={passwordInput}
                            value={data.password}
                            onChange={(e) => setData('password', e.target.value)}
                            type="password"
                            autoComplete="new-password"
                            invalid={!!errors.password}
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
                            id="password_confirmation"
                            value={data.password_confirmation}
                            onChange={(e) => setData('password_confirmation', e.target.value)}
                            type="password"
                            autoComplete="new-password"
                            invalid={!!errors.password_confirmation}
                        />
                        <ErrorMessage>
                            {errors.password_confirmation}
                        </ErrorMessage>
                    </Field>

                    <div className="flex items-center gap-4">
                        <Button type="submit" disabled={processing}>
                            {t('Save')}
                        </Button>

                        <Transition
                            show={recentlySuccessful}
                            enter="transition ease-in-out"
                            enterFrom="opacity-0"
                            leave="transition ease-in-out"
                            leaveTo="opacity-0"
                        >
                            <p className="text-sm text-zinc-600 dark:text-zinc-400">{t('Saved.')}</p>
                        </Transition>
                    </div>
                </FieldGroup>
            </form>
        </section>
    );
}
