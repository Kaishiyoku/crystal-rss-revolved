import {useActionData, useLoaderData} from 'react-router-dom';
import usePageModal from '@/V2/Hooks/usePageModal';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Pane, PaneBody, PaneHeader} from '@/Components/Modal/Pane';
import CreateFeedLoaderType from '@/V2/types/CreateFeedLoaderType';
import CreateFeedValidationErrors from '@/V2/types/CreateFeedValidationErrors';
import FeedForm from '@/V2/Pages/Feeds/Partials/FeedForm';

export default function CreateFeedPage() {
    const {t} = useLaravelReactI18n();
    const {categories} = useLoaderData() as CreateFeedLoaderType;
    const errors = useActionData() as CreateFeedValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/feeds');

    return (
        <Pane appear show={show} onClose={handleClose}>
            <PaneHeader>
                {t('Add feed')}
            </PaneHeader>

            <PaneBody>
                <FeedForm
                    action="/feeds/create"
                    categories={categories}
                    errors={errors}
                />
            </PaneBody>
        </Pane>
    );
}
