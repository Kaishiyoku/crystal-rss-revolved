import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import { Head } from '@inertiajs/react';
import Header from '@/Components/Page/Header';

export default function Dashboard(props) {
    const header = (
        <div>
            <Header>Dashboard</Header>
            <div>{props.totalUnreadFeedItems}</div>
        </div>
    );

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={header}
        >
            <Head title="Dashboard" />
        </AuthenticatedLayout>
    );
}
