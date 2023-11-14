import clsx from 'clsx';
import {ReactNode} from 'react';

const LinkStack = ({children}: { children: ReactNode; }) => {
    return (
        <div className="divide-y divide-gray-200 dark:divide-gray-800">
            {children}
        </div>
    );
};

const Item = (
    {
        href,
        children,
        className = '',
    }: {
        href: string;
        children: ReactNode;
        className?: string;
    }
) => {
    return (
        <a href={href} className={clsx('px-4 py-2 hover:bg-gray-200 hover:dark:bg-gray-800 transition first:rounded-t-lg last:rounded-b-lg', className)}>
            {children}
        </a>
    );
};

LinkStack.Item = Item;

export default LinkStack;
