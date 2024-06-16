import {useActionData, useLoaderData, useParams, useSubmit} from 'react-router-dom';
import usePageModal from '@/V2/Hooks/usePageModal';
import {HeadlessButton} from '@/Components/Button';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Pane, PaneBody, PaneFooter, PaneHeader} from '@/Components/Modal/Pane';
import EditFeedLoaderType from '@/V2/types/EditFeddLoaderType';
import Actions from '@/Components/Actions';
import EditFeedValidationErrors from '@/V2/types/EditFeedValidationErrors';
import FeedForm from '@/V2/Pages/Feeds/Partials/FeedForm';

export default function EditFeedPage() {
    const {t} = useLaravelReactI18n();
    const {feedId} = useParams();
    const {categories, feed, canDelete} = useLoaderData() as EditFeedLoaderType;
    const errors = useActionData() as EditFeedValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/feeds');
    const submit = useSubmit();

    const handleDelete = () => {
        const formData = new FormData();
        formData.append('intent', 'delete');

        submit(formData, {method: 'delete', action: `/feeds/${feedId}/edit`});
    };

    return (
        <Pane appear show={show} onClose={handleClose}>
            <PaneHeader>
                {t('Edit feed')}
            </PaneHeader>

            <PaneBody>
                <FeedForm
                    action={`/feeds/${feedId}/edit`}
                    feed={feed}
                    categories={categories}
                    errors={errors}
                />
            </PaneBody>

            <PaneFooter>
                <Actions footer>
                    {canDelete && (
                        <HeadlessButton
                            confirm
                            confirmTitle={t('Do you really want to delete this feed?')}
                            confirmSubmitTitle={t('Delete feed')}
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
