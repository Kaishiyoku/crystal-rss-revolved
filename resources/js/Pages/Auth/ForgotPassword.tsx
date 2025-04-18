import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import { Button } from "@/Components/Button";
import { useLaravelReactI18n } from "laravel-react-i18n";
import React from "react";
import { Input } from "@/Components/Form/Input";
import { ErrorMessage, Field, FieldGroup } from "@/Components/Fieldset";

export default function ForgotPassword({ status }: { status: string }) {
	const { t } = useLaravelReactI18n();
	const { data, setData, post, processing, errors } = useForm({ email: "" });

	const onHandleChange = (event: React.FormEvent<HTMLInputElement>) => {
		const target = event.target as HTMLInputElement;

		// @ts-expect-error we know which fields can occur here
		setData(target.name, target.value);
	};

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		post(route("password.email"));
	};

	return (
		<GuestLayout>
			<Head title={t("Forgot Password")} />

			<div className="mb-4 text-sm text-zinc-600 dark:text-zinc-400">
				{t(
					"Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.",
				)}
			</div>

			{status && (
				<div className="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
					{status}
				</div>
			)}

			<form onSubmit={submit}>
				<FieldGroup>
					<Field>
						<Input
							id="email"
							type="email"
							name="email"
							value={data.email}
							className="mt-1 block w-full"
							onChange={onHandleChange}
							autoFocus
						/>
						<ErrorMessage>{errors.email}</ErrorMessage>
					</Field>

					<Button type="submit" disabled={processing}>
						{t("Email Password Reset Link")}
					</Button>
				</FieldGroup>
			</form>
		</GuestLayout>
	);
}
