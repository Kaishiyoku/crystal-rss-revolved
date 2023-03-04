import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Form from '@/Pages/Categories/Partials/Form';

export default function Create(props) {
    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>Add category</Header>}
        >
            <Head title="Add category"/>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <Form method="post" action={route('categories.store')} category={props.category}/>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
