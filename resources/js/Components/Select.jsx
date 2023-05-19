import {forwardRef, useEffect, useRef} from 'react';
import clsx from 'clsx';

export default forwardRef(function Select({options = [], className = '', isFocused = false, ...props}, ref) {
    const input = ref || useRef();

    useEffect(() => {
        if (isFocused) {
            input.current.focus();
        }
    }, []);

    return (
        <select
            {...props}
            className={clsx(
                'px-4 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-violet-500 dark:focus:border-violet-600 focus:ring-violet-500 dark:focus:ring-violet-600 rounded-lg shadow-sm transition',
                className,
                {'opacity-50': props.disabled},
            )}
            ref={input}
        >
            {options.map((option) => (
                <option key={option.value} value={option.value}>{option.name}</option>
            ))}
        </select>
    );
});
