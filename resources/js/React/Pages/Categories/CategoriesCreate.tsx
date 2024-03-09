import {Modal, ModalBody, ModalHeader} from '@/Components/Modal/Modal';
import {Form, useActionData} from 'react-router-dom';
import TextInput from '@/Components/TextInput';
import usePageModal from '@/React/Hooks/usePageModal';
import ValidationErrors from '@/React/types/ValidationErrors';
import InputError from '@/Components/InputError';
import {PrimaryButton} from '@/Components/Button';
import InputLabel from '@/Components/InputLabel';
import React from 'react';
import {useLaravelReactI18n} from 'laravel-react-i18n';

type CategoriesCreateValidationErrors = ValidationErrors & { name?: string; } | null;

export default function CategoriesCreate() {
    const {t} = useLaravelReactI18n();
    const errors = useActionData() as CategoriesCreateValidationErrors;
    const {show, handleClose} = usePageModal(errors, '/react/categories');

    return (
        <Modal appear show={show} onClose={handleClose}>
            <ModalHeader>
                {t('Add category')}
            </ModalHeader>

            <ModalBody>
                <Form method="post" action="/react/categories/create" className="space-y-4">
                    <div>
                        <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>
                        <TextInput
                            id="name"
                            name="name"
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
            </ModalBody>
        </Modal>
    );
}
