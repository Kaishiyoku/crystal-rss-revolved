import GuestLayout from '@/Layouts/GuestLayout';
import { Head, Link, useForm } from '@inertiajs/react';
import { Button } from '@/Components/Button';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import type React from 'react';

export default function VerifyEmail({ status }: { status: string }) {
	const { t } = useLaravelReactI18n();
	const { post, processing } = useForm({});

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		post(route('verification.send'));
	};

	return (
		<GuestLayout>
			<Head title="Email Verification" />

			<div className="mb-4 text-sm text-zinc-600 dark:text-zinc-400">
				{t(
					"Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn't receive the email, we will gladly send you another.",
				)}
			</div>

			{status === 'verification-link-sent' && (
				<div className="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
					{t(
						'A new verification link has been sent to the email address you provided during registration.',
					)}
				</div>
			)}

			<form onSubmit={submit}>
				<div className="mt-4 flex items-center justify-between">
					<Button type="submit" disabled={processing}>
						{t('Resend Verification Email')}
					</Button>

					<Link
						href={route('logout')}
						method="post"
						as="button"
						className="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-violet-500 dark:focus:ring-offset-zinc-800"
					>
						{t('Log Out')}
					</Link>
				</div>
			</form>
		</GuestLayout>
	);
}
