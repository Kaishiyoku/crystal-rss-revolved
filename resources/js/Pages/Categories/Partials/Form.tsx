import {useForm} from '@inertiajs/react';
import InputLabel from '@/Components/Form/InputLabel';
import TextInput from '@/Components/Form/TextInput';
import InputError from '@/Components/Form/InputError';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Button} from '@/Components/Button';
import React from 'react';
import Category from '@/types/generated/Models/Category';

export default function Form({method, action, category}: { method: 'post' | 'put'; action: string; category: Category; }) {
    const {t} = useLaravelReactI18n();
    const {data, setData, post, put, errors, processing} = useForm({name: category.name ?? ''});

    const submit = (event: React.FormEvent<HTMLFormElement>) => {
        event.preventDefault();

        const request = method === 'post' ? post : put;

        request(action);
    };

    return (
        <form onSubmit={submit} className="mt-6 space-y-6">
            <div>
                <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>

                <TextInput
                    id="name"
                    className="mt-1 block w-full max-w-xl"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                    isFocused
                />

                <InputError className="mt-2" message={errors.name}/>
            </div>

            <div className="flex items-center gap-4">
                <Button type="submit" disabled={processing}>
                    {t('Save')}
                </Button>
            </div>
        </form>
    );
}
