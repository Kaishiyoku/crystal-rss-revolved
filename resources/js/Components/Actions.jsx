import clsx from 'clsx';

export default function Actions({hasMobileSpacing = false, className, children}) {
    if (!children) {
        return null;
    }

    return (
        <div className={clsx('flex sm:justify-end pb-5', {'px-4 sm:px-0': hasMobileSpacing}, className)}>
            {children}
        </div>
    );
}
