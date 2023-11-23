import clsx from 'clsx';
import noop from '@/Utils/noop';
import React, {FunctionComponent, ReactNode, useState} from 'react';
import {IconProps} from '@/types';
import ConfirmModal from '@/Components/Modal/ConfirmModal';

enum ButtonVariant {
    Headless = 'headless',
    Primary = 'primary',
    Secondary = 'secondary',
    Danger = 'danger',
}

type ButtonProps = {
    icon?: FunctionComponent<IconProps>;
    type?: 'button' | 'submit' | 'reset';
    hasMobileFullSize?: boolean;
    className?: string;
    disabled?: boolean;
    onClick?: (event: React.MouseEvent<HTMLButtonElement>) => void;
    children?: ReactNode;
};

type ConfirmButtonProps = ButtonProps & {
    confirm?: boolean;
    confirmTitle?: string;
    confirmCancelTitle?: string;
    confirmSubmitTitle?: string;
};

const Button = (
    {
        variant,
        icon: Icon = undefined,
        type = 'button',
        hasMobileFullSize = false,
        className = '',
        disabled = false,
        onClick = noop,
        children,
        confirm = false,
        confirmTitle,
        confirmSubmitTitle,
        confirmCancelTitle,
    }: ConfirmButtonProps & { variant: ButtonVariant; }
) => {
    const [showConfirmModal, setShowConfirmModal] = useState(false);

    const handleOnClick = (event: React.MouseEvent<HTMLButtonElement>) => {
        if (confirm) {
            setShowConfirmModal(true);

            return;
        }

        onClick(event);
    };

    return (
        <>
            {confirm && (
                <ConfirmModal
                    show={showConfirmModal}
                    title={confirmTitle!}
                    submitTitle={confirmSubmitTitle!}
                    cancelTitle={confirmCancelTitle!}
                    onClose={() => setShowConfirmModal(false)}
                    onConfirm={(event: React.MouseEvent<HTMLButtonElement>) => {
                        onClick(event);
                        setShowConfirmModal(false);
                    }}
                />
            )}

            <button
                type={type}
                className={clsx(
                    'inline-flex space-x-2 items-center transition ease-in disabled:opacity-50 disabled:saturate-50 disabled:cursor-not-allowed',
                    'text-sm tracking-widest font-semibold focus:ring-1',
                    'hover:shadow-lg hover:shadow-black/20 dark:hover:shadow-black/25 focus:shadow-lg focus:shadow-black/20 dark:focus:shadow-black',
                    {
                        'border rounded-lg px-5 py-2.5': variant !== ButtonVariant.Headless,
                        'shadow-md shadow-black/20 dark:shadow-black/25 border-violet-400 dark:border-violet-900 text-violet-100 bg-violet-600 hover:bg-violet-800 disabled:hover:bg-violet-500 focus:border-violet-600 focus:ring-violet-600': variant === ButtonVariant.Primary,
                        'border-violet-400 dark:border-violet-900 text-violet-600 dark:text-violet-500 dark:hover:text-violet-100 bg-white dark:bg-gray-900 hover:bg-violet-100 dark:hover:bg-violet-950 disabled:hover:text-violet-600 dark:disabled:text-violet-500 disabled:hover:bg-transparent dark:disabled:hover:bg-transparent focus:border-violet-600 focus:ring-violet-600': variant === ButtonVariant.Secondary,
                        'border-pink-400 dark:border-pink-900 text-pink-600 dark:text-pink-500 dark:hover:text-pink-100 bg-white dark:bg-gray-900 hover:bg-pink-100 dark:hover:bg-pink-950 disabled:hover:text-pink-600 dark:disabled:text-pink-500 disabled:hover:bg-transparent dark:disabled:hover:bg-transparent focus:border-pink-600 focus:ring-pink-600': variant === ButtonVariant.Danger,
                        'w-full sm:w-auto': hasMobileFullSize,
                    },
                    className
                )}
                onClick={handleOnClick}
                disabled={disabled}
            >
                {Icon && <Icon className="grow-0 w-5 h-5"/>}

                <span className="grow inline-flex justify-between space-x-2">
                    {children}
                </span>
            </button>
        </>
    );
};

const PrimaryButton = (
    {
        icon,
        type,
        hasMobileFullSize,
        className,
        disabled,
        onClick,
        children,
        confirm = false,
        confirmTitle,
        confirmSubmitTitle,
        confirmCancelTitle,
    }: ConfirmButtonProps) => {
    return (
        <Button
            variant={ButtonVariant.Primary}
            icon={icon}
            type={type}
            hasMobileFullSize={hasMobileFullSize}
            disabled={disabled}
            onClick={onClick}
            className={clsx('', className)}
            confirm={confirm}
            confirmTitle={confirmTitle}
            confirmSubmitTitle={confirmSubmitTitle}
            confirmCancelTitle={confirmCancelTitle}
        >
            {children}
        </Button>
    );
};

const SecondaryButton = (
    {
        icon,
        type,
        hasMobileFullSize,
        className,
        disabled,
        onClick,
        children,
        confirm = false,
        confirmTitle,
        confirmSubmitTitle,
        confirmCancelTitle,
    }: ConfirmButtonProps) => {
    return (
        <Button
            variant={ButtonVariant.Secondary}
            icon={icon}
            type={type}
            hasMobileFullSize={hasMobileFullSize}
            className={className}
            disabled={disabled}
            onClick={onClick}
            confirm={confirm}
            confirmTitle={confirmTitle}
            confirmSubmitTitle={confirmSubmitTitle}
            confirmCancelTitle={confirmCancelTitle}
        >
            {children}
        </Button>
    );
};

const DangerButton = (
    {
        icon,
        type,
        hasMobileFullSize,
        className,
        disabled,
        onClick,
        children,
        confirm = true,
        confirmTitle,
        confirmSubmitTitle,
        confirmCancelTitle,
    }: ConfirmButtonProps) => {
    return (
        <Button
            variant={ButtonVariant.Danger}
            icon={icon}
            type={type}
            hasMobileFullSize={hasMobileFullSize}
            className={className}
            disabled={disabled}
            onClick={onClick}
            confirm={confirm}
            confirmTitle={confirmTitle}
            confirmSubmitTitle={confirmSubmitTitle}
            confirmCancelTitle={confirmCancelTitle}
        >
            {children}
        </Button>
    );
};

const HeadlessButton = (
    {
        icon,
        type,
        hasMobileFullSize,
        className,
        disabled,
        onClick,
        children,
        confirm = false,
        confirmTitle,
        confirmSubmitTitle,
        confirmCancelTitle,
    }: ConfirmButtonProps) => {
    return (
        <Button
            variant={ButtonVariant.Headless}
            icon={icon}
            type={type}
            hasMobileFullSize={hasMobileFullSize}
            className={className}
            disabled={disabled}
            onClick={onClick}
            confirm={confirm}
            confirmTitle={confirmTitle}
            confirmSubmitTitle={confirmSubmitTitle}
            confirmCancelTitle={confirmCancelTitle}
        >
            {children}
        </Button>
    );
};

export {
    PrimaryButton,
    SecondaryButton,
    DangerButton,
    HeadlessButton,
};
