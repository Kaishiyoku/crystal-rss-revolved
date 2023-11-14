import {useState, createContext, useContext, Fragment, PropsWithChildren, Dispatch, SetStateAction} from 'react';
import {InertiaLinkProps, Link} from '@inertiajs/react';
import {Transition} from '@headlessui/react';
import noop from '@/Utils/noop';
import clsx from 'clsx';

type DropDownContextType = {
    open: boolean;
    setOpen: Dispatch<SetStateAction<boolean>>;
    toggleOpen: () => void;
};

const DropDownContext = createContext<DropDownContextType>({
    open: false,
    setOpen: noop,
    toggleOpen: noop,
});

const Dropdown = ({children}: PropsWithChildren) => {
    const [open, setOpen] = useState(false);

    const toggleOpen = () => {
        setOpen((previousState) => !previousState);
    };

    return (
        <DropDownContext.Provider value={{open, setOpen, toggleOpen}}>
            <div className="relative">{children}</div>
        </DropDownContext.Provider>
    );
};

const Trigger = ({children, className = ''}: PropsWithChildren<{ className?: string; }>) => {
    const {open, setOpen, toggleOpen} = useContext(DropDownContext);

    return (
        <>
            <div onClick={toggleOpen} className={className}>{children}</div>

            {open && <div className="fixed inset-0 z-40" onClick={() => setOpen(false)}></div>}
        </>
    );
};

const Content = ({align = 'right', width = 48, contentClasses = 'py-1 bg-white dark:bg-gray-700', children}: PropsWithChildren<{ align?: 'left' | 'right'; width?: 48 | 96; contentClasses?: string; }>) => {
    const {open, setOpen} = useContext(DropDownContext);

    let alignmentClasses = 'origin-top';

    if (align === 'left') {
        alignmentClasses = 'ltr:origin-top-left rtl:origin-top-right start-0';
    } else if (align === 'right') {
        alignmentClasses = 'ltr:origin-top-right rtl:origin-top-left end-0';
    }

    let widthClasses = '';

    if (width === 48) {
        widthClasses = 'w-48';
    } else if (width === 96) {
        widthClasses = 'w-96';
    }

    return (
        <>
            <Transition
                as={Fragment}
                show={open}
                enter="transition ease-out duration-200"
                enterFrom="opacity-0 scale-95"
                enterTo="opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="opacity-100 scale-100"
                leaveTo="opacity-0 scale-95"
            >
                <div
                    className={`absolute z-50 mt-2 rounded-md shadow-lg ${alignmentClasses} ${widthClasses}`}
                    onClick={() => setOpen(false)}
                >
                    <div className={`rounded-md ring-1 ring-black ring-opacity-5 ${contentClasses}`}>
                        {children}
                    </div>
                </div>
            </Transition>
        </>
    );
};

const DropdownLink = ({active = false, className = '', children, ...props}: InertiaLinkProps & { active?: boolean; }) => {
    return (
        <Link
            {...props}
            className={clsx(
                'block w-full px-4 py-2 text-start text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out',
                className,
                {'bg-red-500': active}
            )}
        >
            {children}
        </Link>
    );
};

const Spacer = () => <div className="pt-2 mb-2 border-b dark:border-gray-600"/>;

Dropdown.Trigger = Trigger;
Dropdown.Content = Content;
Dropdown.Link = DropdownLink;
Dropdown.Spacer = Spacer;

export default Dropdown;
