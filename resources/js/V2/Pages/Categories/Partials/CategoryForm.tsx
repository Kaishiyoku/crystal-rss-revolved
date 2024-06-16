import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import InputError from '@/Components/Form/InputError';
import {PrimaryButton} from '@/Components/Button';
import React from 'react';
import CreateCategoryValidationErrors from '@/V2/types/CreateCategoryValidationErrors';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Form} from 'react-router-dom';
import EditCategoryValidationErrors from '@/V2/types/EditCategoryValidationErrors';
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
