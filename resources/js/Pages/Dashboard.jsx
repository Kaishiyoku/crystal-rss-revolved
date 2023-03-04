import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Header from '@/Components/Page/Header';

export default function Dashboard(props) {
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>Dashboard</Header>}
        >
            <Head title="Dashboard" />
        </AuthenticatedLayout>
    );
}
