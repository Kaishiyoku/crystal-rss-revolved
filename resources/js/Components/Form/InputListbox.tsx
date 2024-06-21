import {useState} from 'react';
import {SelectOption} from '@/types/SelectOption';
import {Listbox, ListboxDescription, ListboxLabel, ListboxOption} from '@/Components/Listbox';

type InputListboxType = {
    name: string;
    defaultValue?: number | string;
    placeholder: string;
    options: SelectOption[];
    onChange: (value: number | string) => void;
    errorMessage?: string;
    disabled?: boolean;
    autoFocus?: boolean;
} | {
    name: string;
    defaultValue: number | string;
    placeholder?: string;
    options: SelectOption[];
    onChange: (value: number | string) => void;
    errorMessage?: string;
    disabled?: boolean;
    autoFocus?: boolean;
}

export default function InputListbox({name, defaultValue, placeholder, options, onChange, errorMessage, disabled = false, autoFocus = false}: InputListboxType) {
    const [selected, setSelected] = useState<number | string | null>(defaultValue ?? null);
    const handleChange = (value: number | string) => {
        setSelected(value);

        onChange(value);
    };

    return (
        <>
            <Listbox
                name={name}
                value={selected}
                placeholder={placeholder}
                invalid={!!errorMessage}
                onChange={handleChange}
                disabled={disabled}
                autoFocus={autoFocus}
            >
                {options.map(({value, name, description}) => (
                    <ListboxOption key={value} value={value}>
                        <ListboxLabel>
                            {name}
                        </ListboxLabel>

                        {description && (
                            <ListboxDescription>
                                {description}
                            </ListboxDescription>
                        )}
                    </ListboxOption>
                ))}
            </Listbox>
        </>
    );
}
