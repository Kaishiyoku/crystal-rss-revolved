import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import {PrimaryButton} from '@/Components/Button';
import React from 'react';
import CreateCategoryValidationErrors from '@/types/CreateCategoryValidationErrors';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Form} from 'react-router-dom';
import EditCategoryValidationErrors from '@/types/EditCategoryValidationErrors';
import Category from '@/types/generated/Models/Category';

export default function CategoryForm({action, category = null, errors}: { action: string; category?: Category | null; errors: CreateCategoryValidationErrors | EditCategoryValidationErrors; }) {
    const {t} = useLaravelReactI18n();

    return (
        <Form method="post" action={action} className="space-y-4">
            <div>
                <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>
                <TextInput
                    id="name"
                    name="name"
                    defaultValue={category?.name}
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
    );
}
