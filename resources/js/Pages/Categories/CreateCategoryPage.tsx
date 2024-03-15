import {useActionData} from 'react-router-dom';
import usePageModal from '@/Hooks/usePageModal';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Pane, PaneBody, PaneHeader} from '@/Components/Modal/Pane';
import CreateCategoryValidationErrors from '@/types/CreateCategoryValidationErrors';
import CategoryForm from '@/Pages/Categories/Partials/CategoryForm';

export default function CreateCategoryPage() {
    const {t} = useLaravelReactI18n();
    const errors = useActionData() as CreateCategoryValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/categories');

    return (
        <Pane appear show={show} onClose={handleClose}>
            <PaneHeader>
                {t('Add category')}
            </PaneHeader>

            <PaneBody>
                <CategoryForm
                    action="/categories/create"
                    errors={errors}
                />
            </PaneBody>
        </Pane>
    );
}
