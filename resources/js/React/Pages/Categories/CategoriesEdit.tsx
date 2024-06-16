import {Modal, ModalBody, ModalHeader} from '@/Components/Modal/Modal';
import {Form, useActionData, useLoaderData, useParams} from 'react-router-dom';
import TextInput from '@/Components/TextInput';
import usePageModal from '@/React/Hooks/usePageModal';
import ValidationErrors from '@/React/types/ValidationErrors';
import InputError from '@/Components/InputError';
import {PrimaryButton} from '@/Components/Button';
import InputLabel from '@/Components/InputLabel';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import Category from '@/types/generated/Models/Category';

type CategoriesCreateValidationErrors = ValidationErrors & { name?: string; } | null;

export default function CategoriesEdit() {
    const {t} = useLaravelReactI18n();
    const {categoryId} = useParams();
    const category = useLoaderData() as Category;
    const errors = useActionData() as CategoriesCreateValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/react/categories');

    return (
        <Modal appear show={show} onClose={handleClose}>
            <ModalHeader>
                {t('Edit category')}
            </ModalHeader>

            <ModalBody>
                <Form method="put" action={`/react/categories/${categoryId}/edit`}>
                    <div className="mb-4">
                        <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>
                        <TextInput
                            id="name"
                            name="name"
                            defaultValue={category.name}
                            className="block w-full max-w-xl"
                            required
                            isFocused
                        />
                        <InputError message={errors?.name}/>
                    </div>

                    <PrimaryButton type="submit">
                        {t('Save')}
                    </PrimaryButton>
                </Form>
            </ModalBody>
        </Modal>
    );
}
