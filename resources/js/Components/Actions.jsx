import clsx from 'clsx';

export default function Actions({withMobileSpacing = false, className, children}) {
    return (
        <div className={clsx('flex justify-end pb-5', {'px-4 sm:px-0': withMobileSpacing}, className)}>
            {children}
        </div>
    );
}
