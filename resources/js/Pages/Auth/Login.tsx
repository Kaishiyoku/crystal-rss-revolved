import type React from 'react';
import { useEffect } from 'react';
import { Checkbox, CheckboxField } from '@/Components/Form/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/Components/Button';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { ErrorMessage, Field, FieldGroup, Label } from '@/Components/Fieldset';
import { Input } from '@/Components/Form/Input';

export default function Login({
	status,
	canResetPassword,
}: { status: string; canResetPassword: boolean }) {
	const { t } = useLaravelReactI18n();
	const { data, setData, post, processing, errors, reset } = useForm({
		email: '',
		password: '',
		remember: false as boolean,
	});

	// biome-ignore lint/correctness/useExhaustiveDependencies(reset): we only want to run this once
	useEffect(() => {
		return () => {
			reset('password');
		};
	}, []);

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		post(route('login'));
	};

	return (
		<GuestLayout>
			<Head title={t('Log in')} />

			{status && (
				<div className="mb-4 font-medium text-sm text-green-600">{status}</div>
			)}

			<form onSubmit={submit}>
				<FieldGroup>
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
							autoFocus
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
							autoComplete="current-password"
							onChange={(event) => setData('password', event.target.value)}
						/>
						<ErrorMessage>{errors.password}</ErrorMessage>
					</Field>

					<CheckboxField>
						<Checkbox
							name="remember"
							checked={data.remember}
							onChange={(checked) => setData('remember', checked)}
						/>
						<Label>{t('Remember me')}</Label>
					</CheckboxField>

					<div className="flex items-center justify-end mt-4">
						{canResetPassword && (
							<Link
								href={route('password.request')}
								className="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-800"
							>
								{t('Forgot your password?')}
							</Link>
						)}

						<Button type="submit" className="ml-4" disabled={processing}>
							{t('Log in')}
						</Button>
					</div>
				</FieldGroup>
			</form>
		</GuestLayout>
	);
}
