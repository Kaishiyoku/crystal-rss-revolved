import { Head, useForm } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import Form from "@/Pages/Categories/Partials/Form";
import { useLaravelReactI18n } from "laravel-react-i18n";
import { PageProps } from "@/types";
import { RouteParams } from "ziggy-js";
import { Button } from "@/Components/Button";
import { useState } from "react";
import ConfirmAlert from "@/Components/ConfirmAlert";
import { Category } from "@/types/generated/models";

export default function Edit({
	category,
	canDelete,
	...props
}: PageProps & { category: Category; canDelete: boolean }) {
	const { t } = useLaravelReactI18n();
	const { delete: destroy, processing } = useForm();

	const handleDelete = () => {
		setIsConfirmDeleteAlertOpen(false);

		destroy(
			route(
				"categories.destroy",
				category as unknown as RouteParams<"categories.destroy">,
			),
		);
	};

	const [isConfirmDeleteAlertOpen, setIsConfirmDeleteAlertOpen] =
		useState(false);

	return (
		<AuthenticatedLayout
			auth={props.auth}
			errors={props.errors}
			breadcrumbs={props.breadcrumbs}
			actions={
				<>
					{canDelete && (
						<>
							<Button
								disabled={processing}
								onClick={() => setIsConfirmDeleteAlertOpen(true)}
								outline
							>
								{t("Delete")}
							</Button>

							<ConfirmAlert
								open={isConfirmDeleteAlertOpen}
								title={t("Do you really want to delete this category?")}
								confirmTitle={t("Delete category")}
								onClose={() => setIsConfirmDeleteAlertOpen(false)}
								onConfirm={handleDelete}
							/>
						</>
					)}
				</>
			}
		>
			<Head title={t("Edit category")} />

			<Form
				method="put"
				action={route(
					"categories.update",
					category as unknown as RouteParams<"categories.update">,
				)}
				category={category}
			/>
		</AuthenticatedLayout>
	);
}
