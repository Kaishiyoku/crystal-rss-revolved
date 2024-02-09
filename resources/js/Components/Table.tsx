import React, {Children, cloneElement, ReactNode} from 'react';
import clsx from 'clsx';
import {head} from 'ramda';

const Table = ({children}: {children: ReactNode;}) => {
    const headings = Children.toArray(children)
        // @ts-expect-error it doesn't matter what type child is because it must be some kind of clonable element
        .filter((child) => child.type.name === Heading.name)
        .map((child, index, arr) => {
            // @ts-expect-error it doesn't matter what type child is because it must be some kind of clonable element
            return cloneElement(child, {className: clsx(child.props.className, {'rounded-tl-md': index === 0, 'rounded-tr-md': index === arr.length - 1})});
        });

    // @ts-expect-error it doesn't matter what type child is because it must be some kind of clonable element
    const rows = Children.toArray(children).filter((child) => child.type.name === Row.name);

    return (
        <table className="min-w-full border-separate border-spacing-0">
            <thead>
                <tr>
                    {headings}
                </tr>
            </thead>
            <tbody>
                {rows}
            </tbody>
        </table>
    );
};

const Heading = ({children, hideOnMobile = false, className = ''}: {children: ReactNode; hideOnMobile?: boolean; className?: string;}) => {
    return (
        <th
            className={clsx(
                'sticky top-0 z-10 border-b border-gray-300 bg-white bg-opacity-75 py-3.5 pl-4 pr-3 text-left font-semibold text-gray-900 backdrop-blur backdrop-filter sm:pl-6 lg:pl-8',
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

const Row = ({children}: {children: ReactNode;}) => {
    const mobileContainer = head(
        Children.toArray(children)
            // @ts-expect-error it doesn't matter what type child is because it must be some kind of clonable element
            .filter((child) => child.type.name === MobileContainer.name)
    );

    const cells = Children.toArray(children)
        // @ts-expect-error it doesn't matter what type child is because it must be some kind of clonable element
        .filter((child) => child.type.name === Cell.name)
        .map((child, index) => {
            if (index === 0) {
                // @ts-expect-error it doesn't matter what type child is because it must be some kind of clonable element
                return <Cell key={index} highlighted={child.props.highlighted} hideOnMobile={child.props.hideOnMobile}>{child.props.children}{mobileContainer}</Cell>;
            }

            return child;
        });

    return (
        <tr className="even:bg-gray-50 odd:bg-white">
            {cells}
        </tr>
    );
};

const Cell = ({children, highlighted = false, hideOnMobile = false}: {children: ReactNode; highlighted?: boolean; hideOnMobile?: boolean;}) => {
    return (
        <td
            className={clsx(
                'align-top border-b border-gray-200 whitespace-nowrap py-4 pl-4 pr-3 font-medium text-gray-900 sm:pl-6 lg:pl-8',
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

Table.Heading = Heading;
Table.Row = Row;
Table.Cell = Cell;
Table.MobileContainer = MobileContainer;
Table.MobileText = MobileText;

export default Table;
