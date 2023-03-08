import {Link, Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Actions from '@/Components/Actions';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function Index(props) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Categories')}</Header>}
            withMobileSpacing
        >
            <Head title={t('Categories')}/>

            <Actions>
                <Link
                    href={route('categories.create')}
                    className="font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >
                    {t('Add category')}
                </Link>
            </Actions>

            {props.categories.map((category) => (
                <Link
                    key={category.id}
                    href={route('categories.edit', category)}
                    className="block font-semibold text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white focus:outline focus:outline-2 focus:rounded-sm focus:outline-red-500"
                >
                    {category.name}
                </Link>
            ))}
        </AuthenticatedLayout>
    );
}
