import {useActionData, useLoaderData, useParams, useSubmit} from 'react-router-dom';
import usePageModal from '@/V2/Hooks/usePageModal';
import {HeadlessButton} from '@/Components/Button';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Pane, PaneBody, PaneFooter, PaneHeader} from '@/Components/Modal/Pane';
import EditCategoryLoaderType from '@/V2/types/EditCategoryLoaderType';
import Actions from '@/Components/Actions';
import CategoryForm from '@/V2/Pages/Categories/Partials/CategoryForm';
import EditCategoryValidationErrors from '@/V2/types/EditCategoryValidationErrors';

export default function EditCategoryPage() {
    const {t} = useLaravelReactI18n();
    const {categoryId} = useParams();
    const {category, canDelete} = useLoaderData() as EditCategoryLoaderType;
    const errors = useActionData() as EditCategoryValidationErrors;
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
                <CategoryForm
                    action={`/app/categories/${categoryId}/edit`}
                    category={category}
                    errors={errors}
                />
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
