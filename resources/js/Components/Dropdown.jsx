import {useState, createContext, useContext, Fragment} from 'react';
import {Link} from '@inertiajs/react';
import {Transition} from '@headlessui/react';
import clsx from 'clsx';

const DropDownContext = createContext();

const Dropdown = ({children}) => {
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

const Trigger = ({children}) => {
    const {open, setOpen, toggleOpen} = useContext(DropDownContext);

    return (
        <>
            <div onClick={toggleOpen}>{children}</div>

            {open && <div className="fixed inset-0 z-40" onClick={() => setOpen(false)}></div>}
        </>
    );
};

const Content = ({align = 'right', width = 48, contentClasses = 'p-2 border dark:border-gray-700 bg-white/50 dark:bg-gray-800/80', children}) => {
    const {open, setOpen} = useContext(DropDownContext);

    let alignmentClasses = 'origin-top';

    if (align === 'left') {
        alignmentClasses = 'origin-top-left left-0';
    } else if (align === 'right') {
        alignmentClasses = 'origin-top-right right-0';
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
                enterFrom="transform opacity-0 scale-95"
                enterTo="transform opacity-100 scale-100"
                leave="transition ease-in duration-75"
                leaveFrom="transform opacity-100 scale-100"
                leaveTo="transform opacity-0 scale-95"
            >
                <div
                    className={`absolute z-50 mt-2 rounded-md shadow-lg dark:shadow-black/25 backdrop-blur-lg max-w-full sm:max-w-none ${alignmentClasses} ${widthClasses}`}
                    onClick={() => setOpen(false)}
                >
                    <div className={clsx('max-h-[350px] overflow-y-auto scrollbar-y-sm rounded-md ring-1 ring-black ring-opacity-5', contentClasses)}>
                        <div>
                            {children}
                        </div>
                    </div>
                </div>
            </Transition>
        </>
    );
};

const DropdownLink = ({component = Link, active = false, className = '', children, ...props}) => {
    const Component = component;

    return (
        <Component
            {...props}
            className={clsx(
                'block w-full text-left px-4 py-2 text-sm leading-5 border-l-4 rounded focus:outline-none transition duration-150 ease-in-out  border-transparent',
                {
                    'text-indigo-300 bg-indigo-700 hover:bg-indigo-600 focus:bg-indigo-500 focus:text-indigo-50': active,
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:bg-gray-200 dark:focus:bg-gray-600 dark:focus:text-gray-300': !active,
                },
                className
            )}
        >
            {children}
        </Component>
    );
};

Dropdown.Trigger = Trigger;
Dropdown.Content = Content;
Dropdown.Link = DropdownLink;

export default Dropdown;
