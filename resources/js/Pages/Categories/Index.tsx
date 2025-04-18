import { Head } from "@inertiajs/react";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout";
import { useLaravelReactI18n } from "laravel-react-i18n";
import { EmptyState } from "@/Components/EmptyState";
import { PageProps } from "@/types";
import { RouteParams } from "ziggy-js";
import { Button } from "@/Components/Button";
import { LinkStack, LinkStackItem } from "@/Components/LinkStack";
import { FolderIcon } from "@heroicons/react/24/outline";
import { PlusIcon } from "@heroicons/react/16/solid";
import CategoryWithFeedsCount from "@/types/models/CategoryWithFeedsCount";

export default function Index({
	categories,
	...props
}: PageProps & { categories: CategoryWithFeedsCount[] }) {
	const { t, tChoice } = useLaravelReactI18n();

	return (
		<AuthenticatedLayout
			auth={props.auth}
			errors={props.errors}
			header={t("Categories")}
			actions={
				<>
					{categories.length > 0 && (
						<Button href={route("categories.create")} outline>
							{t("Add category")}
						</Button>
					)}
				</>
			}
		>
			<Head title={t("Categories")} />

			{categories.length > 0 ? (
				<LinkStack>
					{categories.map((category) => (
						<LinkStackItem
							key={category.id}
							title={category.name}
							url={route(
								"categories.edit",
								category as unknown as RouteParams<"categories.edit">,
							)}
						>
							<div className="text-muted">
								{tChoice("category.feeds_count", category.feeds_count)}
							</div>
						</LinkStackItem>
					))}
				</LinkStack>
			) : (
				<EmptyState
					icon={FolderIcon}
					message={t("No categories.")}
					description={t("Get started by creating a new category.")}
				>
					<Button href={route("categories.create")} outline>
						<PlusIcon />

						{t("New category")}
					</Button>
				</EmptyState>
			)}
		</AuthenticatedLayout>
	);
}
