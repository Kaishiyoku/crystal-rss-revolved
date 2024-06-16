import {
    createContext,
    Dispatch,
    Fragment,
    PropsWithChildren,
    ReactNode,
    SetStateAction,
    useContext,
    useState
} from 'react';
import {Transition} from '@headlessui/react';
import noop from '@/Utils/noop';
import clsx from 'clsx';
import {NavLink} from 'react-router-dom';
import {HeadlessButton} from '@/Components/Button';

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

const Content = ({align = 'right', width = 48, contentClasses = 'p-2 bg-white/80 dark:bg-gray-800/80', children}: PropsWithChildren<{ align?: 'left' | 'right'; width?: 48 | 96; contentClasses?: string; }>) => {
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
                    className={`absolute z-50 mt-2 rounded-lg shadow-lg dark:shadow-black/25 backdrop-blur-lg max-w-full sm:max-w-none ${alignmentClasses} ${widthClasses}`}
                    onClick={() => setOpen(false)}
                >
                    <div className={clsx('max-h-[350px] overflow-y-auto scrollbar-y-sm rounded-lg ring-1 ring-black ring-opacity-5', contentClasses)}>
                        {children}
                    </div>
                </div>
            </Transition>
        </>
    );
};

const DropdownLink = ({to, active = undefined, external = false, className = '', children}: { to: string; active?: boolean | undefined; external?: boolean; className?: string; children: ReactNode; }) => {
    const {setOpen} = useContext(DropDownContext);

    if (external) {
        return (
            <a
                href={to}
                className={clsx(
                    'block w-full text-left px-4 py-2 text-sm leading-5 rounded-lg focus:outline-none transition duration-150 ease-in-out text-gray-700 dark:text-gray-400 hover:bg-gray-400/25 dark:hover:bg-gray-700 focus:bg-gray-500/25 dark:focus:bg-gray-600 dark:focus:text-gray-300',
                    className
                )}
            >
                {children}
            </a>
        );
    }

    return (
        <NavLink
            to={to}
            end
            onClick={() => setOpen(false)}
            className={({isActive}) => clsx(
                'block w-full text-left px-4 py-3 sm:py-2 text-sm leading-5 rounded-lg focus:outline-none transition duration-150 ease-in-out',
                {
                    'text-violet-100 bg-violet-500 hover:bg-violet-600 focus:bg-violet-500 focus:text-violet-50': active !== undefined ? active : isActive,
                    'text-gray-700 dark:text-gray-400 hover:bg-gray-400/25 dark:hover:bg-gray-700 focus:bg-gray-500/25 dark:focus:bg-gray-600 dark:focus:text-gray-300': active !== undefined ? !active : !isActive,
                },
                className
            )}
        >
            {children}
        </NavLink>
    );
};

const DropdownButton = ({onClick, className = '', children}: { onClick: () => void; className?: string; children: ReactNode; }) => {
    return (
        <HeadlessButton
            onClick={onClick}
            className={clsx(
                'block w-full text-left px-4 py-3 sm:py-2 text-sm leading-5 rounded-lg focus:outline-none transition duration-150 ease-in-out text-gray-700 dark:text-gray-400 hover:bg-gray-400/25 dark:hover:bg-gray-700 focus:bg-gray-500/25 dark:focus:bg-gray-600 dark:focus:text-gray-300',
                className
            )}
        >
            {children}
        </HeadlessButton>
    );
};

const Spacer = () => <div className="pt-2 mb-2 border-b dark:border-gray-600"/>;

Dropdown.Trigger = Trigger;
Dropdown.Content = Content;
Dropdown.Link = DropdownLink;
Dropdown.Button = DropdownButton;
Dropdown.Spacer = Spacer;

export default Dropdown;
