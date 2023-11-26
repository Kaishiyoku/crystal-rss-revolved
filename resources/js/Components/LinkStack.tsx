import clsx from 'clsx';
import {ReactNode} from 'react';
import Card from '@/Components/Card';

const LinkStack = ({children}: { children: ReactNode; }) => {
    return (
        <Card>
            {children}
        </Card>
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
        <a href={href} className={clsx('px-4 py-3 hover:bg-gray-50 hover:dark:bg-gray-800 transition ease-in-out first:rounded-t-lg last:rounded-b-lg', className)}>
            {children}
        </a>
    );
};

LinkStack.Item = Item;

export default LinkStack;
