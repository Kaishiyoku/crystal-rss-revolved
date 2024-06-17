import {forwardRef, InputHTMLAttributes, useEffect, useImperativeHandle, useRef} from 'react';
import clsx from 'clsx';

export default forwardRef(function TextInput(
    {type = 'text', className = '', isFocused = false, ...props}: InputHTMLAttributes<HTMLInputElement> & { isFocused?: boolean; },
    ref
) {
    const localRef = useRef<HTMLInputElement>(null);

    useImperativeHandle(ref, () => ({
        focus: () => localRef.current?.focus(),
    }));

    useEffect(() => {
        if (isFocused) {
            localRef.current?.focus();
        }
    }, []);

    return (
        <input
            {...props}
            type={type}
            className={clsx(
                'px-4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-lg shadow-inner dark:shadow-black/25 transition',
                className,
                {'opacity-50': props.disabled}
            )}
            ref={localRef}
        />
    );
});
