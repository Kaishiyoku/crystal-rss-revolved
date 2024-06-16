import {Form, useActionData, useLoaderData, useParams, useSubmit} from 'react-router-dom';
import TextInput from '@/Components/TextInput';
import usePageModal from '@/V2/Hooks/usePageModal';
import ValidationErrors from '@/V2/types/ValidationErrors';
import InputError from '@/Components/InputError';
import {HeadlessButton, PrimaryButton} from '@/Components/Button';
import InputLabel from '@/Components/InputLabel';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Pane, PaneBody, PaneFooter, PaneHeader} from '@/Components/Modal/Pane';
import CategoryLoaderType from '@/V2/types/CategoryLoaderType';
import Actions from '@/Components/Actions';

type CategoriesCreateValidationErrors = ValidationErrors & { name?: string; } | null;

export default function EditCategoryPage() {
    const {t} = useLaravelReactI18n();
    const {categoryId} = useParams();
    const {category, canDelete} = useLoaderData() as CategoryLoaderType;
    const errors = useActionData() as CategoriesCreateValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/app/categories');
    const submit = useSubmit();

    const handleDelete = () => {
        const formData = new FormData();
        formData.append('intent', 'delete');

        submit(formData, {method: 'delete', action: `/app/categories/${categoryId}/edit`});
    };

    return (
        <Pane appear show={show} onClose={handleClose}>
            <PaneHeader>
                {t('Edit category')}
            </PaneHeader>

            <PaneBody>
                <Form method="put" action={`/app/categories/${categoryId}/edit`} className="space-y-4">
                    <div>
                        <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>
                        <TextInput
                            id="name"
                            name="name"
                            defaultValue={category.name}
                            className="block w-full"
                            required
                            isFocused
                        />
                        <InputError message={errors?.name}/>
                    </div>

                    <PrimaryButton type="submit">
                        {t('Save')}
                    </PrimaryButton>
                </Form>
            </PaneBody>

            <PaneFooter>
                <Actions footer>
                    {canDelete && (
                        <HeadlessButton
                            confirm
                            confirmTitle={t('Do you really want to delete this category?')}
                            confirmSubmitTitle={t('Delete category')}
                            confirmCancelTitle={t('Cancel')}
                            onClick={handleDelete}
                            className="link-danger"
                        >
                            {t('Delete')}
                        </HeadlessButton>
                    )}
                </Actions>
            </PaneFooter>
        </Pane>
    );
}
