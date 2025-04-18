import type React from "react";
import { useEffect } from "react";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import { Button } from "@/Components/Button";
import { useLaravelReactI18n } from "laravel-react-i18n";
import { ErrorMessage, Field, FieldGroup, Label } from "@/Components/Fieldset";
import { Input } from "@/Components/Form/Input";

export default function ConfirmPassword() {
	const { t } = useLaravelReactI18n();
	const { data, setData, post, processing, errors, reset } = useForm({
		password: "",
	});

    // biome-ignore lint/correctness/useExhaustiveDependencies(reset): we only want to run this once
	useEffect(() => {
		return () => {
			reset("password");
		};
	}, []);

	const handleOnChange = (event: React.FormEvent<HTMLInputElement>) => {
		const target = event.target as HTMLInputElement;

		// @ts-expect-error we know which fields can occur here
		setData(target.name, target.value);
	};

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		post(route("password.confirm"));
	};

	return (
		<GuestLayout>
			<Head title={t("Confirm Password")} />

			<div className="mb-4 text-sm text-zinc-600 dark:text-zinc-400">
				{t(
					"This is a secure area of the application. Please confirm your password before continuing.",
				)}
			</div>

			<form onSubmit={submit}>
				<FieldGroup>
					<Field>
						<Label htmlFor="password">{t("Password")}</Label>
						<Input
							id="password"
							type="password"
							name="password"
							value={data.password}
							className="mt-1 block w-full"
							onChange={handleOnChange}
							autoFocus
						/>

						<ErrorMessage>{errors.password}</ErrorMessage>
					</Field>

					<Button type="submit" className="ml-4" disabled={processing}>
						{t("Confirm")}
					</Button>
				</FieldGroup>
			</form>
		</GuestLayout>
	);
}
