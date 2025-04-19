import { useForm } from '@inertiajs/react';
import { useLaravelReactI18n } from 'laravel-react-i18n';
import { Button } from '@/Components/Button';
import type React from 'react';
import { ErrorMessage, Field, FieldGroup, Label } from '@/Components/Fieldset';
import { Input } from '@/Components/Form/Input';
import type { Category } from '@/types/generated/models';

export default function Form({
	method,
	action,
	category,
}: { method: 'post' | 'put'; action: string; category?: Category }) {
	const { t } = useLaravelReactI18n();
	const { data, setData, post, put, errors, processing } = useForm({
		name: category?.name ?? '',
	});

	const submit = (event: React.FormEvent<HTMLFormElement>) => {
		event.preventDefault();

		const request = method === 'post' ? post : put;

		request(action);
	};

	return (
		<form onSubmit={submit}>
			<FieldGroup>
				<Field>
					<Label htmlFor="name" required>
						{t('validation.attributes.name')}
					</Label>
					<Input
						id="name"
						className="mt-1 block w-full max-w-xl"
						value={data.name}
						onChange={(e) => setData('name', e.target.value)}
						autoFocus
						required
					/>
					<ErrorMessage>{errors.name}</ErrorMessage>
				</Field>

				<Button type="submit" disabled={processing}>
					{t('Save')}
				</Button>
			</FieldGroup>
		</form>
	);
}
