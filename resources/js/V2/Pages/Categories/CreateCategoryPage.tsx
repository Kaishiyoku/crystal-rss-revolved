import {useActionData} from 'react-router-dom';
import usePageModal from '@/V2/Hooks/usePageModal';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Pane, PaneBody, PaneHeader} from '@/Components/Modal/Pane';
import CreateCategoryValidationErrors from '@/V2/types/CreateCategoryValidationErrors';
import CategoryForm from '@/V2/Pages/Categories/Partials/CategoryForm';

export default function CreateCategoryPage() {
    const {t} = useLaravelReactI18n();
    const errors = useActionData() as CreateCategoryValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/app/categories');

    return (
        <Pane appear show={show} onClose={handleClose}>
            <PaneHeader>
                {t('Add category')}
            </PaneHeader>

            <PaneBody>
                <CategoryForm action="/app/categories/create" errors={errors}/>
            </PaneBody>
        </Pane>
    );
}
