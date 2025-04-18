import { Link, useForm, usePage } from "@inertiajs/react";
import { Transition } from "@headlessui/react";
import { useLaravelReactI18n } from "laravel-react-i18n";
import { Button } from "@/Components/Button";
import type React from "react";
import { Description, Heading } from "@/Components/Heading";
import { ErrorMessage, Field, FieldGroup, Label } from "@/Components/Fieldset";
import { Input } from "@/Components/Form/Input";
import type { PageProps } from "@/types";

export default function UpdateProfileInformation({
	mustVerifyEmail,
	status,
	className,
}: { mustVerifyEmail: boolean; status: string; className?: string }) {
	const { t } = useLaravelReactI18n();
	const user = usePage<PageProps>().props.auth.user;

	const { data, setData, patch, errors, processing, recentlySuccessful } =
		useForm({
			name: user.name,
			email: user.email,
		});

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		patch(route("profile.update"));
	};

	return (
		<section className={className}>
			<header className="pb-6">
				<Heading level={2}>{t("Profile Information")}</Heading>

				<Description>
					{t("Update your account's profile information and email address.")}
				</Description>
			</header>

			<form onSubmit={submit}>
				<FieldGroup>
					<Field>
						<Label htmlFor="name">{t("Name")}</Label>
						<Input
							id="name"
							className="mt-1 block w-full"
							value={data.name}
							onChange={(e) => setData("name", e.target.value)}
							autoComplete="name"
							invalid={!!errors.name}
							autoFocus
							required
						/>
						<ErrorMessage>{errors.name}</ErrorMessage>
					</Field>

					<Field>
						<Label htmlFor="email">{t("Email")}</Label>
						<Input
							id="email"
							type="email"
							className="mt-1 block w-full"
							value={data.email}
							onChange={(e) => setData("email", e.target.value)}
							autoComplete="username"
							invalid={!!errors.email}
							required
						/>
						<ErrorMessage>{errors.email}</ErrorMessage>
					</Field>

					{mustVerifyEmail && user.email_verified_at === null && (
						<div>
							<p className="text-sm mt-2 text-zinc-800 dark:text-zinc-200">
								{t("Your email address is unverified.")}
								<Link
									href={route("verification.send")}
									method="post"
									as="button"
									className="underline text-sm text-zinc-600 dark:text-zinc-400 hover:text-zinc-900 dark:hover:text-zinc-100 rounded-md focus:outline-hidden focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-zinc-800"
								>
									{t("Click here to re-send the verification email.")}
								</Link>
							</p>

							{status === "verification-link-sent" && (
								<div className="mt-2 font-medium text-sm text-green-600 dark:text-green-400">
									{t(
										"A new verification link has been sent to your email address.",
									)}
								</div>
							)}
						</div>
					)}

					<div className="flex items-center gap-4">
						<Button type="submit" disabled={processing}>
							{t("Save")}
						</Button>

						<Transition
							show={recentlySuccessful}
							enter="transition ease-in-out"
							enterFrom="opacity-0"
							leave="transition ease-in-out"
							leaveTo="opacity-0"
						>
							<p className="text-sm text-zinc-600 dark:text-zinc-400">
								{t("Saved.")}
							</p>
						</Transition>
					</div>
				</FieldGroup>
			</form>
		</section>
	);
}
