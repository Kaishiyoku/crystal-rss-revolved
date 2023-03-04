import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import Form from '@/Pages/Feeds/Partials/Form';
import DangerButton from '@/Components/DangerButton';

export default function Edit(props) {
    const {delete: destroy, processing} = useForm();

    const handleDelete = () => {
        destroy(route('feeds.destroy', props.feed));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>Edit feed</Header>}
        >
            <Head title="Edit feed"/>

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {props.canDelete && (
                        <DangerButton disabled={processing} onClick={handleDelete} className="mb-5">
                            Delete
                        </DangerButton>
                    )}

                    <Form
                        method="put"
                        action={route('feeds.update', props.feed)}
                        feed={props.feed}
                        categories={props.categories}
                    />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
