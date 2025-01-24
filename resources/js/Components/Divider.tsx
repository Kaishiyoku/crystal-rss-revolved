import clsx from 'clsx';

export function Divider({soft = false, className, ...props}: { soft?: boolean; } & React.ComponentPropsWithoutRef<'hr'>) {
    return (
        <hr
            role="presentation"
            {...props}
            className={clsx(
                className,
                'w-full border-t',
                soft && 'border-zinc-950/5 dark:border-white/5',
                !soft && 'border-zinc-950/10 dark:border-white/10'
            )}
        />
    );
}

export function DividerContainer({soft = false, className, ...props}: { soft?: boolean; } & React.ComponentPropsWithoutRef<'div'>) {
    return (
        <div
            {...props}
            className={clsx(
                className,
                'divide-y *:py-8',
                soft && 'divide-zinc-950/5 dark:divide-white/5',
                !soft && 'divide-zinc-950/10 dark:divide-white/10'
            )}
        />
    );
}
