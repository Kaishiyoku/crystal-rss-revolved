import clsx from 'clsx';
import noop from '@/Utils/noop';
import React, {FunctionComponent, ReactNode, useState} from 'react';
import {IconProps} from '@/types';
import ConfirmModal from '@/Components/Modal/ConfirmModal';

enum ButtonVariant {
    Headless = 'headless',
    Primary = 'primary',
    Secondary = 'secondary',
    Tertiary = 'tertiary',
    Danger = 'danger',
}

type ButtonProps = {
    icon?: FunctionComponent<IconProps>;
    type?: 'button' | 'submit' | 'reset';
    hasMobileFullSize?: boolean;
    className?: string;
    disabled?: boolean;
    onClick?: (event: React.MouseEvent<HTMLButtonElement>) => void;
    name?: string;
    value?: string | number;
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
        name,
        value,
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
            event.preventDefault();

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
                name={name}
                value={value}
                className={clsx(
                    'inline-flex space-x-2 items-center text-left transition ease-in disabled:opacity-50 disabled:saturate-50 disabled:cursor-not-allowed',
                    {
                        'font-semibold text-sm tracking-widest rounded-lg px-5 py-2.5 shadow dark:shadow-black/25 focus:ring-0 focus:ring-black dark:focus:ring-white focus:shadow-none active:shadow-none': variant !== ButtonVariant.Headless,
                        'shadow-md shadow-black/20 dark:shadow-black/25 text-violet-100 bg-violet-500 hover:bg-violet-700 disabled:hover:bg-violet-500': variant === ButtonVariant.Primary,
                        'text-gray-600 dark:text-gray-400 dark:hover:text-gray-100 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 disabled:hover:text-gray-600 dark:disabled:text-gray-500 disabled:hover:bg-white dark:disabled:hover:bg-gray-800': variant === ButtonVariant.Secondary,
                        'text-gray-600 dark:text-gray-400 dark:hover:text-gray-100 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 disabled:hover:text-gray-600 dark:disabled:text-gray-500 disabled:hover:bg-white dark:disabled:hover:bg-gray-800': variant === ButtonVariant.Tertiary,
                        'text-pink-950 dark:text-pink-300 dark:hover:text-pink-200 bg-pink-200 dark:bg-pink-900 hover:bg-pink-300 dark:hover:bg-pink-800 disabled:hover:text-pink-600 dark:disabled:text-pink-500 disabled:hover:bg-white dark:disabled:hover:bg-pink-950': variant === ButtonVariant.Danger,
                        'w-full sm:w-auto': hasMobileFullSize,
                    },
                    className
                )}
                onClick={handleOnClick}
                disabled={disabled}
            >
                {Icon && <Icon className="grow-0 shrink-0 w-5 h-5"/>}

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

const TertiaryButton = (
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
            variant={ButtonVariant.Tertiary}
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
        name,
        value,
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
            name={name}
            value={value}
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
    TertiaryButton,
    DangerButton,
    HeadlessButton,
};
