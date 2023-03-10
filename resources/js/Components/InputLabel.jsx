import clsx from 'clsx';

export default function InputLabel({ value, required = false, className = '', children, ...props }) {
    return (
        <label {...props} className={clsx('block font-medium text-sm text-gray-700 dark:text-gray-300', className, {'label-required': required})}>
            {value ? value : children}
        </label>
    );
}
