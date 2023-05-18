import PropTypes from 'prop-types';
import clsx from 'clsx';
import noop from '@/Utils/noop';
import {useState} from 'react';
import ConfirmModal from '@/Components/Modal/ConfirmModal';

const Button = ({as: Component = 'button', variant, type = 'button', hasMobileFullSize = false, className = '', confirm = false, disabled, children, onClick = noop, ...props}) => {
    const [showConfirmModal, setShowConfirmModal] = useState(false);

    const handleOnClick = () => {
        if (confirm) {
            setShowConfirmModal(true);

            return;
        }

        onClick();
    };

    const additionalProps = Component === 'button' ? {type} : {};

    return (
        <>
            <ConfirmModal
                show={showConfirmModal}
                onClose={() => setShowConfirmModal(false)}
                onConfirm={() => {
                    onClick(); setShowConfirmModal(false);
                }}
            />

            <Component
                className={clsx(
                    'inline-flex items-center transition ease-in disabled:opacity-50 disabled:saturate-50 disabled:cursor-not-allowed',
                    'text-sm tracking-widest font-semibold focus:ring-1',
                    'rounded-full px-5 sm:px-4 py-3.5 sm:py-2.5',
                    'focus:shadow-md dark:focus:shadow-black/20',
                    {
                        'text-violet-100 bg-violet-500 hover:bg-violet-600 disabled:hover:bg-violet-500 focus:ring-violet-600': variant === 'primary',
                        'text-violet-700 dark:text-violet-500 hover:text-violet-800 dark:hover:text-violet-300 border border-violet-300 dark:border-violet-500 dark:hover:border-violet-800 hover:bg-violet-200 dark:hover:bg-violet-800 dark:disabled:text-violet-500 disabled:dark:hover:border-violet-500 disabled:hover:bg-transparent dark:disabled:hover:bg-transparent focus:ring-violet-600': variant === 'secondary',
                        'text-pink-100 bg-pink-500 hover:bg-pink-600 disabled:hover:bg-pink-500 focus:ring-pink-600': variant === 'danger',
                        'text-violet-700 dark:text-violet-500 dark:hover:text-violet-300 hover:bg-violet-200 dark:hover:bg-violet-800 dark:disabled:hover:text-violet-500 disabled:bg-transparent dark:disabled:bg-transparent focus:ring-violet-400 dark:focus:ring-violet-600': variant === 'plain',
                        'w-full sm:w-auto': hasMobileFullSize,
                    },
                    className
                )}
                onClick={handleOnClick}
                disabled={disabled}
                {...props}
                {...additionalProps}
            >
                {children}
            </Component>
        </>
    );
};
Button.propTypes = {
    as: PropTypes.any,
    variant: PropTypes.oneOf(['primary', 'secondary', 'danger', 'plain']).isRequired,
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    hasMobileFullSize: PropTypes.bool,
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
    as: PropTypes.any,
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    hasMobileFullSize: PropTypes.bool,
    className: PropTypes.any,
    confirm: PropTypes.bool,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

const SecondaryButton = ({className, ...props}) => {
    return (
        <Button
            variant="secondary"
            className={className}
            {...props}
        />
    );
};
SecondaryButton.propTypes = {
    as: PropTypes.any,
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    hasMobileFullSize: PropTypes.bool,
    className: PropTypes.any,
    confirm: PropTypes.bool,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

const DangerButton = ({confirm = true, className, ...props}) => {
    return (
        <Button
            variant="danger"
            className={clsx('', className)}
            confirm={confirm}
            {...props}
        />
    );
};
DangerButton.propTypes = {
    as: PropTypes.any,
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    hasMobileFullSize: PropTypes.bool,
    className: PropTypes.any,
    confirm: PropTypes.bool,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

const PlainButton = ({className, ...props}) => {
    return (
        <Button
            variant="plain"
            className={className}
            {...props}
        />
    );
};
PlainButton.propTypes = {
    as: PropTypes.any,
    type: PropTypes.oneOf(['button', 'submit', 'reset']),
    hasMobileFullSize: PropTypes.bool,
    className: PropTypes.any,
    confirm: PropTypes.bool,
    disabled: PropTypes.bool,
    onClick: PropTypes.func,
};

export {
    PrimaryButton,
    SecondaryButton,
    DangerButton,
    PlainButton,
};
