import {OtherProps} from '@/types';

export default function InputError({message, className = '', ...props}: {message: string; className?: string, props: OtherProps}) {
    return message
        ? (
            <p {...props} className={'text-sm text-red-600 dark:text-red-400 ' + className}>
                {message}
            </p>
        )
        : null;
}
