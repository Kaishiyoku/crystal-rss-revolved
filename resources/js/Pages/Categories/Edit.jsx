import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import DangerButton from '@/Components/DangerButton';
import Form from '@/Pages/Categories/Partials/Form';
import Actions from '@/Components/Actions';

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

            <Actions>
                {props.canDelete && (
                    <DangerButton disabled={processing} onClick={handleDelete}>
                        Delete
                    </DangerButton>
                )}
            </Actions>

            <Form method="put" action={route('categories.update', props.category)} category={props.category}/>
        </AuthenticatedLayout>
    );
}
