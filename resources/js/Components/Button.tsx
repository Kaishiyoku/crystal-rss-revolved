import clsx from 'clsx';
import noop from '@/Utils/noop';
import React, {FunctionComponent, ReactNode} from 'react';
import {IconProps} from '@/types';

enum ButtonVariant {
    Headless = 'headless',
    Primary = 'primary',
    Secondary = 'secondary',
    Danger = 'danger',
    Plain = 'plain',
}

const Button = (
    {
        variant,
        icon: Icon = undefined,
        type = 'button',
        hasMobileFullSize = false,
        className = '',
        disabled = false,
        children,
        onClick = noop,
    }: {
        variant: ButtonVariant;
        icon?: FunctionComponent<IconProps>;
        type?: 'button' | 'submit' | 'reset';
        hasMobileFullSize?: boolean;
        className?: string;
        disabled?: boolean;
        children: ReactNode;
        onClick?: (event: React.MouseEvent<HTMLButtonElement>) => void;
    }
) => {
    return (
        <button
            type={type}
            className={clsx(
                'inline-flex space-x-2 items-center transition ease-in disabled:opacity-50 disabled:saturate-50 disabled:cursor-not-allowed',
                'text-sm tracking-widest font-semibold focus:ring-1',
                'focus:shadow-md dark:focus:shadow-black/20',
                {
                    'rounded-lg px-5 sm:px-4 py-3.5 sm:py-2.5': variant !== ButtonVariant.Headless,
                    'text-violet-100 bg-violet-500 hover:bg-violet-600 disabled:hover:bg-violet-500 focus:ring-violet-600': variant === ButtonVariant.Primary,
                    'text-violet-600 dark:text-violet-500 hover:text-violet-100 dark:hover:text-violet-100 border border-violet-600 dark:border-violet-500 dark:hover:border-violet-800 bg-white dark:bg-gray-900 hover:bg-violet-600 disabled:hover:text-violet-600 dark:disabled:text-violet-500 disabled:dark:hover:border-violet-500 disabled:hover:bg-transparent dark:disabled:hover:bg-transparent focus:ring-violet-600': variant === ButtonVariant.Secondary,
                    'text-pink-100 bg-pink-500 hover:bg-pink-600 disabled:hover:bg-pink-500 focus:ring-pink-600': variant === ButtonVariant.Danger,
                    'text-violet-600 dark:text-violet-500 hover:text-violet-100 dark:hover:text-violet-100 hover:bg-violet-600 disabled:hover:text-violet-600 dark:disabled:hover:text-violet-500 disabled:bg-transparent dark:disabled:bg-transparent focus:ring-violet-400 dark:focus:ring-violet-600': variant === ButtonVariant.Plain,
                    'w-full sm:w-auto': hasMobileFullSize,
                },
                className
            )}
            onClick={onClick}
            disabled={disabled}
        >
            {Icon && <Icon className="grow-0 w-5 h-5"/>}

            <span className="grow inline-flex justify-between space-x-2">
                {children}
            </span>
        </button>
    );
};

const PrimaryButton = ({children, className = ''}: { children: ReactNode; className?: string; }) => {
    return (
        <Button
            variant={ButtonVariant.Primary}
            className={clsx('', className)}
        >
            {children}
        </Button>
    );
};

const SecondaryButton = (
    {
        children,
        icon = undefined,
        className = '',
        disabled = false,
        hasMobileFullSize = false,
        onClick = noop,
    }: {
        children: ReactNode;
        icon?: FunctionComponent<IconProps>;
        className?: string;
        disabled?: boolean;
        hasMobileFullSize?: boolean;
        onClick?: (event: React.MouseEvent<HTMLButtonElement>) => void;
    }
) => {
    return (
        <Button
            variant={ButtonVariant.Secondary}
            icon={icon}
            disabled={disabled}
            hasMobileFullSize={hasMobileFullSize}
            className={className}
            onClick={onClick}
        >
            {children}
        </Button>
    );
};

const DangerButton = (
    {
        children,
        onClick = noop,
        className = '',
    }: {
        children: ReactNode;
        onClick: (event: React.MouseEvent<HTMLButtonElement>) => void;
        className?: string;
    }
) => {
    return (
        <Button
            variant={ButtonVariant.Danger}
            className={clsx('', className)}
            onClick={onClick}
        >
            {children}
        </Button>
    );
};

const PlainButton = ({children, className = ''}: { children: ReactNode; className?: string; }) => {
    return (
        <Button
            variant={ButtonVariant.Plain}
            className={className}
        >
            {children}
        </Button>
    );
};

const HeadlessButton = ({children, className = ''}: { children: ReactNode; className?: string; }) => {
    return (
        <Button
            variant={ButtonVariant.Headless}
            className={className}
        >
            {children}
        </Button>
    );
};

export {
    PrimaryButton,
    SecondaryButton,
    DangerButton,
    PlainButton,
    HeadlessButton,
};
