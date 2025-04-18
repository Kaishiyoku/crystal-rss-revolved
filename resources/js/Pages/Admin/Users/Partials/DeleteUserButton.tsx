import ConfirmAlert from "@/Components/ConfirmAlert";
import { Button } from "@/Components/Button";
import { useLaravelReactI18n } from "laravel-react-i18n";
import { useState } from "react";
import { useForm } from "@inertiajs/react";
import { RouteParams } from "ziggy-js";
import UserWithStats from "@/types/models/UserWithStats";

export default function DeleteUserButton({ user }: { user: UserWithStats }) {
	const { t } = useLaravelReactI18n();

	const [isConfirmAlertOpen, setIsConfirmAlertOpen] = useState(false);

	const { delete: destroy, processing: deleteProcessing } = useForm();

	const handleDelete = (user: UserWithStats) => () => {
		destroy(
			route(
				"admin.users.destroy",
				user as unknown as RouteParams<"admin.users.destroy">,
			),
		);
	};

	return (
		<>
			<Button
				onClick={() => setIsConfirmAlertOpen(true)}
				disabled={deleteProcessing}
			>
				{t("Delete")}
			</Button>

			<ConfirmAlert
				open={isConfirmAlertOpen}
				title={t("Do you really want to delete the user “:name”?", {
					name: user.name,
				})}
				confirmTitle={t("Delete user")}
				onClose={() => setIsConfirmAlertOpen(false)}
				onConfirm={handleDelete(user)}
			/>
		</>
	);
}
