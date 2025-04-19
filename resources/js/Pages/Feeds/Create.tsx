import { Head } from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { EmptyState } from '@/Components/EmptyState';
import type { PageProps } from '@/types';
import type { SelectNumberOption } from '@/types/SelectOption';
import { FolderIcon, PlusIcon } from '@heroicons/react/24/outline';
import { Button } from '@/Components/Button';

export default function Create({
	categories,
	...props
}: PageProps & { categories: SelectNumberOption[] }) {
	const { t } = useLaravelReactI18n();

	return (
		<AuthenticatedLayout
			auth={props.auth}
			errors={props.errors}
			breadcrumbs={props.breadcrumbs}
		>
			<Head title={t('Add feed')} />

			{categories.length > 0 ? (
				<Form
					method="post"
					action={route('feeds.store')}
					categories={categories}
				/>
			) : (
				<EmptyState
					icon={FolderIcon}
					message={t('Please create a category first.')}
					description={t(
						'There have to be at least one category before you can create a feed.',
					)}
				>
					<Button href={route('categories.create')} className="mt-2" outline>
						<PlusIcon />
						{t('Add category')}
					</Button>
				</EmptyState>
			)}
		</AuthenticatedLayout>
	);
}
