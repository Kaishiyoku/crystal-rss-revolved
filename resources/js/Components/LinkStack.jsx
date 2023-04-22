import clsx from 'clsx';

const LinkStack = ({children}) => {
    return (
        <div className="divide-y divide-gray-200 dark:divide-gray-800">
            {children}
        </div>
    );
};

const Item = ({href, children, className, ...props}) => {
    return (
        <a href={href} className={clsx('px-4 py-2 hover:bg-gray-200 hover:dark:bg-gray-800 transition sm:first:rounded-t-md sm:last:rounded-b-md', className)}>
            {children}
        </a>
    );
};

LinkStack.Item = Item;

export default LinkStack;
