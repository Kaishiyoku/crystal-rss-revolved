import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Form from '@/Pages/Feeds/Partials/Form';

export default function Index(props) {
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>Add feed</Header>}
        >
            <Head title="Add feed"/>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <Form
                        method="post"
                        action={route('feeds.store')}
                        feed={props.feed}
                        categories={props.categories}
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
