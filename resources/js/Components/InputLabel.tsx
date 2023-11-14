import clsx from 'clsx';
import {OtherProps} from '@/types';

export default function InputLabel(
    {
        value,
        required = false,
        className = '',
        ...props
    }: {
        value: string;
        required?: boolean;
        className?: string;
        props: OtherProps;
    }
) {
    return (
        <label {...props} className={clsx('block font-medium text-sm text-gray-700 dark:text-gray-300', className, {'label-required': required})}>
            {value}
        </label>
    );
}
