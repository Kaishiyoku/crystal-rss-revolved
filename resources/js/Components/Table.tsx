import React, {ReactNode} from 'react';
import clsx from 'clsx';

const Table = ({children}: {children: ReactNode;}) => {
    return (
        <table className="min-w-full border-separate border-spacing-0">
            {children}
        </table>
    );
};

const HeadingRow = ({children}: {children: ReactNode;}) => {
    return (
        <tr>
            {children}
        </tr>
    );
};

const HeadingCell = ({children, hideOnMobile = false, className = ''}: {children: ReactNode; hideOnMobile?: boolean; className?: string;}) => {
    return (
        <th
            className={clsx(
                'sticky top-0 z-10 border-b border-gray-300 dark:border-gray-500 bg-white dark:bg-gray-800 bg-opacity-75 py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 dark:text-gray-100 backdrop-blur backdrop-filter sm:pl-6 lg:pl-6',
                className,
                {
                    'hidden lg:table-cell': hideOnMobile,
                }
            )}
        >
            {children}
        </th>
    );
};

const Row = ({children}: { children: ReactNode; }) => {
    return (
        <tr className="even:bg-gray-50 dark:even:bg-gray-900 odd:bg-white dark:odd:bg-gray-800">
            {children}
        </tr>
    );
};

const Cell = ({children, highlighted = false, hideOnMobile = false}: {children: ReactNode; highlighted?: boolean; hideOnMobile?: boolean;}) => {
    return (
        <td
            className={clsx(
                'align-top border-b border-gray-200 dark:border-gray-600 whitespace-nowrap py-4 pl-4 pr-3 font-medium sm:pl-6 lg:pl-8',
                {
                    'font-semibold': highlighted,
                    'hidden lg:table-cell': hideOnMobile,
                }
            )}
        >
            {children}
        </td>
    );
};

const MobileContainer = ({children}: {children: ReactNode;}) => <dl className="lg:hidden pt-2 space-y-2 font-normal">{children}</dl>;

const MobileText = ({label, children}: {label: string; children: ReactNode;}) => {
    return (
        <div>
            <dt className="text-sm text-muted">{label}</dt>
            <dd className="truncate">{children}</dd>
        </div>
    );
};

Table.HeadingRow = HeadingRow;
Table.HeadingCell = HeadingCell;
Table.Row = Row;
Table.Cell = Cell;
Table.MobileContainer = MobileContainer;
Table.MobileText = MobileText;

export default Table;
