import type React from 'react';
import { useEffect } from 'react';
import { Checkbox, CheckboxField } from '@/Components/Form/Checkbox';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm } from '@inertiajs/react';
import { Button } from '@/Components/Button';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { ErrorMessage, Field, Label } from '@/Components/Fieldset';
import { Input } from '@/Components/Form/Input';
import ApplicationLogo from '@/Components/ApplicationLogo';
import { Heading } from '@/Components/Heading';
import { Strong, Text, TextLink } from '@/Components/Text';
import { Link } from '@/Components/Link';

export default function Login({
	status,
	canResetPassword,
}: {
	status: string;
	canResetPassword: boolean;
}) {
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
		<GuestLayout minimal>
			<Head title={t('Log in')} />

			<form
				onSubmit={submit}
				className="grid w-full max-w-sm grid-cols-1 gap-8"
			>
				<Link href="/" className="flex items-center space-x-2 w-fit">
					<ApplicationLogo className="h-6" />

					<div className="text-lg font-semibold bg-linear-to-r from-blue-700 via-violet-700 to-teal-700 dark:from-blue-600 dark:via-violet-600 dark:to-teal-600 bg-clip-text text-transparent">
						{window.appName}
					</div>
				</Link>

				{status && (
					<div className="mb-4 font-medium text-sm text-green-600">
						{status}
					</div>
				)}

				<Heading>{t('Sign in to your account')}</Heading>

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

				<div className="flex items-center justify-between">
					<CheckboxField>
						<Checkbox
							name="remember"
							checked={data.remember}
							onChange={(checked) => setData('remember', checked)}
						/>
						<Label>{t('Remember me')}</Label>
					</CheckboxField>

					{canResetPassword && (
						<Text>
							<TextLink href={route('password.request')}>
								<Strong>{t('Forgot your password?')}</Strong>
							</TextLink>
						</Text>
					)}
				</div>

				<Button type="submit" className="w-full" disabled={processing}>
					{t('Log in')}
				</Button>

				<Text>
					{t('Don’t have an account?')}{' '}
					<TextLink href={route('register')}>
						<Strong>{t('Sign up')}</Strong>
					</TextLink>
				</Text>
			</form>
		</GuestLayout>
	);
}
