import noop from '@/Components/Utils/noop';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function SecondaryButton({type = 'button', className = '', confirm = false, disabled, children, onClick = noop, ...props}) {
    const {t} = useLaravelReactI18n();

    const handleOnClick = () => {
        if (confirm) {
            if (window.confirm(t('Are you sure?'))) {
                onClick();
            }

            return;
        }

        onClick();
    };

    return (
        <button
            {...props}
            type={type}
            className={
                `inline-flex items-center px-4 py-4 sm:py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 ${
                    disabled && 'opacity-25'
                } ` + className
            }
            onClick={handleOnClick}
            disabled={disabled}
        >
            {children}
        </button>
    );
}
