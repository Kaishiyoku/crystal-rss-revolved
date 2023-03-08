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
                    className="link-secondary"
                >
                    {t('Add category')}
                </Link>
            </Actions>

            {props.categories.map((category) => (
                <Link
                    key={category.id}
                    href={route('categories.edit', category)}
                    className="block link-secondary"
                >
                    {category.name}
                </Link>
            ))}
        </AuthenticatedLayout>
    );
}
