import clsx from 'clsx';
import {ReactNode} from 'react';
import Card from '@/Components/Card';
import {Link} from 'react-router-dom';

const LinkStack = ({children}: { children: ReactNode; }) => {
    return (
        <Card>
            {children}
        </Card>
    );
};

const Item = (
    {
        to,
        children,
        className = '',
    }: {
        to: string;
        children: ReactNode;
        className?: string;
    }
) => {
    return (
        <Link
            to={to}
            className={clsx('px-4 py-3 hover:bg-gray-50 hover:dark:bg-gray-700 transition ease-in-out first:rounded-t-lg last:rounded-b-lg', className)}
        >
            {children}
        </Link>
    );
};

LinkStack.Item = Item;

export default LinkStack;
