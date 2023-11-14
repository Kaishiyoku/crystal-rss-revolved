import {OtherProps} from '@/types';

export default function Checkbox({className = '', ...props}: {className?: string, props: OtherProps}) {
    return (
        <input
            {...props}
            type="checkbox"
            className={
                'rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-violet-600 shadow-sm focus:ring-violet-500 dark:focus:ring-violet-600 dark:focus:ring-offset-gray-800 ' +
                className
            }
        />
    );
}
