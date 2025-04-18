import type React from 'react';
import {useEffect} from 'react';
import GuestLayout from '@/Layouts/GuestLayout';
import {Head, Link, useForm} from '@inertiajs/react';
import {Button} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {ErrorMessage, Field, FieldGroup, Label} from '@/Components/Fieldset';
import {Input} from '@/Components/Form/Input';

export default function Register() {
	const { t } = useLaravelReactI18n();
	const { data, setData, post, processing, errors, reset } = useForm({
		name: '',
		email: '',
		password: '',
		password_confirmation: '',
	});

	// biome-ignore lint/correctness/useExhaustiveDependencies(reset): we only want to run this once
	useEffect(() => {
		return () => {
			reset('password', 'password_confirmation');
		};
	}, []);

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		post(route('register'));
	};

	return (
		<GuestLayout>
			<Head title={t('Register')} />

			<form onSubmit={submit}>
				<FieldGroup>
					<Field>
						<Label htmlFor="name">{t('Name')}</Label>
						<Input
							id="name"
							name="name"
							value={data.name}
							className="mt-1 block w-full"
							autoComplete="name"
                            onChange={(event) => setData('name', event.target.value)}
							autoFocus
							required
						/>
						<ErrorMessage>{errors.name}</ErrorMessage>
					</Field>

					<Field>
						<Label htmlFor="email">{t('Email')}</Label>
						<Input
							id="email"
							type="email"
							name="email"
							value={data.email}
							className="mt-1 block w-full"
							autoComplete="username"
                            onChange={(event) => setData('email', event.target.value)}
							required
						/>
						<ErrorMessage>{errors.email}</ErrorMessage>
					</Field>

					<Field>
						<Label htmlFor="password">{t('Password')}</Label>
						<Input
							id="password"
							type="password"
							name="password"
							value={data.password}
							className="mt-1 block w-full"
							autoComplete="new-password"
                            onChange={(event) => setData('password', event.target.value)}
							required
						/>
						<ErrorMessage>{errors.password}</ErrorMessage>
					</Field>

					<Field>
						<Label htmlFor="password_confirmation">
							{t('Confirm Password')}
						</Label>
						<Input
							id="password_confirmation"
							type="password"
							name="password_confirmation"
							value={data.password_confirmation}
							className="mt-1 block w-full"
							autoComplete="new-password"
							onChange={(event) => setData('password_confirmation', event.target.value)}
							required
						/>
						<ErrorMessage>{errors.password_confirmation}</ErrorMessage>
					</Field>

					<div className="flex items-center justify-end mt-4">
						<Link
							href={route('login')}
							className="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-zinc-800"
						>
							{t('Already registered?')}
						</Link>

						<Button type="submit" className="ml-4" disabled={processing}>
							{t('Register')}
						</Button>
					</div>
				</FieldGroup>
			</form>
		</GuestLayout>
	);
}
