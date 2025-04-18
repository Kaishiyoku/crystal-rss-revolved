import { type FormEventHandler, useRef, useState } from "react";
import { useForm } from "@inertiajs/react";
import { useLaravelReactI18n } from "laravel-react-i18n";
import { Button } from "@/Components/Button";
import { Input } from "@/Components/Form/Input";
import { ErrorMessage, Field, Label } from "@/Components/Fieldset";
import {
	Dialog,
	DialogActions,
	DialogBody,
	DialogDescription,
	DialogTitle,
} from "@/Components/Dialog";

export default function DeleteUserForm({
	className = "",
}: { className?: string }) {
	const { t } = useLaravelReactI18n();

	const [confirmingUserDeletion, setConfirmingUserDeletion] = useState(false);
	const passwordInput = useRef<HTMLInputElement>(null);

	const {
		data,
		setData,
		delete: destroy,
		processing,
		reset,
		errors,
	} = useForm({
		password: "",
	});

	const confirmUserDeletion = () => {
		setConfirmingUserDeletion(true);
	};

	const deleteUser: FormEventHandler = (e) => {
		e.preventDefault();

		destroy(route("profile.destroy"), {
			preserveScroll: true,
			onSuccess: () => closeModal(),
			onError: () => passwordInput.current?.focus(),
			onFinish: () => reset(),
		});
	};

	const closeModal = () => {
		setConfirmingUserDeletion(false);

		reset();
	};

	return (
		<section className={`space-y-6 ${className}`}>
			<header>
				<h2 className="text-lg font-medium text-zinc-900 dark:text-zinc-100">
					{t("Delete Account")}
				</h2>

				<p className="mt-1 text-sm text-zinc-600 dark:text-zinc-400">
					{t(
						"Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.",
					)}
				</p>
			</header>

			<Button onClick={confirmUserDeletion} color="pink">
				{t("Delete Account")}
			</Button>

			<Dialog open={confirmingUserDeletion} onClose={closeModal}>
				<DialogTitle>
					{t("Are you sure you want to delete your account?")}
				</DialogTitle>

				<DialogDescription>
					{t(
						"Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.",
					)}
				</DialogDescription>

				<DialogBody>
					<form onSubmit={deleteUser}>
						<Field>
							<Label htmlFor="password" className="sr-only">
								{t("validation.attributes.password")}
							</Label>
							<Input
								id="password"
								type="password"
								name="password"
								ref={passwordInput}
								value={data.password}
								onChange={(e) => setData("password", e.target.value)}
								className="w-3/4"
								placeholder={t("validation.attributes.password")}
								invalid={!!errors.password}
								autoFocus
							/>
							<ErrorMessage>{errors.password}</ErrorMessage>
						</Field>

						<DialogActions>
							<Button onClick={closeModal} plain>
								{t("Cancel")}
							</Button>

							<Button type="submit" disabled={processing} color="pink">
								{t("Delete Account")}
							</Button>
						</DialogActions>
					</form>
				</DialogBody>
			</Dialog>
		</section>
	);
}
