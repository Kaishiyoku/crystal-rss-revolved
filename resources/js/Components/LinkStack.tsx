import clsx from 'clsx';
import {ReactNode} from 'react';
import {Link} from 'react-router-dom';

const LinkStack = ({children}: { children: ReactNode; }) => {
    return (
        <div>
            {children}
        </div>
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
            className={clsx('px-4 py-3 hover:bg-gray-100 hover:dark:bg-gray-800 transition ease-in-out first:rounded-t-lg last:rounded-b-lg', className)}
        >
            {children}
        </Link>
    );
};

LinkStack.Item = Item;

export default LinkStack;
