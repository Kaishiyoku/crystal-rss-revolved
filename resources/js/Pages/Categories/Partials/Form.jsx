import {useForm} from '@inertiajs/react';
import InputLabel from '@/Components/InputLabel';
import TextInput from '@/Components/TextInput';
import InputError from '@/Components/InputError';
import PrimaryButton from '@/Components/PrimaryButton';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function Form({method, action, category}) {
    const {t} = useLaravelReactI18n();
    const {data, setData, post, put, errors, processing, recentlySuccessful} = useForm({
        name: category.name ?? '',
    });

    const submit = (e) => {
        e.preventDefault();

        const request = method === 'post' ? post : put;

        request(action);
    };

    return (
        <form onSubmit={submit} className="mt-6 space-y-6">
            <div>
                <InputLabel htmlFor="name" value={t('validation.attributes.name')} required/>

                <TextInput
                    id="name"
                    className="mt-1 block w-full"
                    value={data.name}
                    onChange={(e) => setData('name', e.target.value)}
                    required
                    isFocused
                />

                <InputError className="mt-2" message={errors.name}/>
            </div>

            <div className="flex items-center gap-4">
                <PrimaryButton disabled={processing}>
                    {t('Save')}
                </PrimaryButton>
            </div>
        </form>
    );
}
