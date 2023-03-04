import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import DangerButton from '@/Components/DangerButton';
import Form from '@/Pages/Categories/Partials/Form';

export default function Index(props) {
    const {delete: destroy, processing} = useForm();

    const handleDelete = () => {
        destroy(route('categories.destroy', props.category));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>Edit category</Header>}
        >
            <Head title="Edit category" />

            <div className="py-12">
                <div className="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {props.canDelete && (
                        <DangerButton disabled={processing} onClick={handleDelete}>
                            Delete
                        </DangerButton>
                    )}

                    <Form method="put" action={route('categories.update', props.category)} category={props.category}/>
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
