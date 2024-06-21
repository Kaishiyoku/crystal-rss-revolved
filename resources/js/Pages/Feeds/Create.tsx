import {Head, Link} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Form from '@/Pages/Feeds/Partials/Form';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import EmptyState from '@/Components/EmptyState';
import PlusOutlineIcon from '@/Icons/PlusOutlineIcon';
import {PageProps} from '@/types';
import {SelectNumberOption} from '@/types/SelectOption';
import Feed from '@/types/generated/Models/Feed';
import {PlusIcon, TagIcon} from '@heroicons/react/24/outline';
import {Button} from '@/Components/Button';

export default function Create({feed, categories, ...props}: PageProps & { feed: Feed; categories: SelectNumberOption[]; }) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            breadcrumbs={props.breadcrumbs}
        >
            <Head title={t('Add feed')}/>

            {categories.length > 0
                ? (
                    <Form
                        method="post"
                        action={route('feeds.store')}
                        feed={feed}
                        categories={categories}
                    />
                )
                : (
                    <EmptyState
                        icon={TagIcon}
                        message={t('Please create a category first.')}
                        description={t('There have to be at least one category before you can create a feed.')}
                    >
                        <Button
                            href={route('categories.create')}
                            className="mt-2"
                            plain
                        >
                            <PlusIcon/>
                            {t('New category')}
                        </Button>
                    </EmptyState>
                )}
        </AuthenticatedLayout>
    );
}
