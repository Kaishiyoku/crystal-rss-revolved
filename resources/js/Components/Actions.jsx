import clsx from 'clsx';

export default function Actions({withMobileSpacing = false, className, children}) {
    if (!children) {
        return null;
    }

    return (
        <div className={clsx('flex justify-end pb-5', {'px-4 sm:px-0': withMobileSpacing}, className)}>
            {children}
        </div>
    );
}
