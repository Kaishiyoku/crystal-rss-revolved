import noop from '@/Utils/noop';

export default function Checkbox(
    {
        name,
        checked,
        value,
        disabled = false,
        onChange = noop,
        className = '',
    }: {
        name: string;
        checked?: boolean;
        value: string | number | boolean;
        disabled?: boolean;
        onChange?: (event: React.FormEvent<HTMLInputElement>) => void;
        className?: string;
    }
) {
    return (
        <input
            type="checkbox"
            name={name}
            checked={checked}
            value={value.toString()}
            disabled={disabled}
            onChange={onChange}
            className={
                'rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-violet-600 shadow-sm focus:ring-violet-500 dark:focus:ring-violet-600 dark:focus:ring-offset-gray-800 ' +
                className
            }
        />
    );
}
