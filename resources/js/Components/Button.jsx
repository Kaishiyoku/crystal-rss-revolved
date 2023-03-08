import noop from '@/Components/Utils/noop';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import PropTypes from 'prop-types';
import clsx from 'clsx';

const Button = ({variant, type = 'button', className = '', confirm = false, disabled, children, onClick = noop, ...props}) => {
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
            className={clsx(
                'inline-flex items-center transition ease-in disabled:opacity-50 disabled:cursor-not-allowed',
                'font-semibold text-xs uppercase tracking-widest border focus:ring-1',
                'shadow focus:shadow-md dark:shadow-black',
                'rounded-md px-4 py-3 sm:py-2',
                {
                    'text-white border-indigo-600 bg-indigo-500 hover:border-indigo-700 hover:bg-indigo-600 focus:ring-indigo-600 dark:border-indigo-500 dark:bg-indigo-600 dark:hover:border-indigo-600 dark:hover:bg-indigo-700 dark:focus:ring-indigo-500': variant === 'primary',
                    'text-gray-900 border-gray-300 bg-white hover:border-gray-300 hover:bg-gray-100 focus:ring-gray-300 dark:text-white dark:border-gray-600 dark:bg-gray-700 dark:hover:border-gray-500 dark:hover:bg-gray-600 dark:focus:ring-gray-600': variant === 'secondary',
                    'text-white border-pink-600 bg-pink-500 hover:border-pink-700 hover:bg-pink-600 focus:ring-pink-600 dark:border-pink-500 dark:bg-pink-600 dark:hover:border-pink-600 dark:hover:bg-pink-700 dark:focus:ring-pink-500': variant === 'danger',

                }
            )}
            onClick={handleOnClick}
            disabled={disabled}
        >
            {children}
        </button>
    );
};
Button.propTypes = {
    variant: PropTypes.oneOf(['primary', 'secondary', 'danger']).isRequired,
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    className: PropTypes.any,
    confirm: PropTypes.bool,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

const PrimaryButton = ({className, ...props}) => {
    return (
        <Button
            variant="primary"
            className={clsx('', className)}
            {...props}
        />
    );
};
PrimaryButton.propTypes = {
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    className: PropTypes.any,
    confirm: PropTypes.bool,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

const SecondaryButton = ({className, ...props}) => {
    return (
        <Button
            variant="secondary"
            className={clsx('', className)}
            {...props}
        />
    );
};
SecondaryButton.propTypes = {
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    className: PropTypes.any,
    confirm: PropTypes.bool,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

const DangerButton = ({confirm, className, ...props}) => {
    return (
        <Button
            variant="danger"
            className={clsx('', className)}
            confirm
            {...props}
        />
    );
};
DangerButton.propTypes = {
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    className: PropTypes.any,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

export {
    PrimaryButton,
    SecondaryButton,
    DangerButton,
};
