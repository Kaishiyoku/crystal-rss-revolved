import clsx from 'clsx';

export default function InputLabel(
    {
        htmlFor,
        value,
        required = false,
        className = '',
    }: {
        htmlFor: string;
        value: string;
        required?: boolean;
        className?: string;
    }
) {
    return (
        <label
            htmlFor={htmlFor}
            className={clsx('block font-medium text-sm text-gray-700 dark:text-gray-300', className, {'label-required': required})}
        >
            {value}
        </label>
    );
}
