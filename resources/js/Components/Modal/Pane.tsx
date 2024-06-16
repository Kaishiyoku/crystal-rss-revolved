import {Fragment, ReactNode, useEffect} from 'react';
import {Dialog, Transition} from '@headlessui/react';
import {HeadlessButton} from '@/Components/Button';
import XMarkOutlineIcon from '@/Icons/XMarkOutlineIcon';

const Pane = (
    {
        children,
        appear = false,
        show = false,
        closeable = true,
        onClose = () => {},
    }: {
        children: ReactNode;
        appear?: boolean;
        show?: boolean;
        closeable?: boolean;
        onClose?: () => void;
    }
) => {
    useEffect(() => {
        document.body.style.overflowY = show ? 'hidden' : '';
    }, [show]);

    const handleClose = () => {
        if (closeable) {
            onClose();
        }
    };

    return (
        <Transition appear={appear} show={show} as={Fragment} leave="duration-200">
            <Dialog
                as="div"
                id="modal"
                className="fixed inset-0 flex justify-end max-h-full z-50 transform transition-all backdrop-blur"
                onClose={handleClose}
            >
                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                >
                    <div className="absolute inset-0 bg-gray-500/75 dark:bg-gray-900/75"/>
                </Transition.Child>

                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0 translate-x-20"
                    enterTo="opacity-100 translate-x-0 sm:scale-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100 translate-x-0 sm:scale-100"
                    leaveTo="opacity-0 translate-x-20"
                >
                    <Dialog.Panel
                        className="flex flex-col bg-white dark:bg-gray-800 sm:rounded-lg max-h-full shadow-xl transform transition-all w-full sm:max-w-sm"
                    >
                        {closeable && (
                            <HeadlessButton onClick={onClose} className="absolute top-0 right-0 mt-2 mr-2 button-icon">
                                <XMarkOutlineIcon className="w-5 h-5"/>
                            </HeadlessButton>
                        )}

                        {children}
                    </Dialog.Panel>
                </Transition.Child>
            </Dialog>
        </Transition>
    );
};

const PaneHeader = ({children}: { children: ReactNode; }) => {
    return (
        <div className="p-6">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-gray-100 pr-8">
                {children}
            </h2>
        </div>
    );
};

const PaneBody = ({children}: { children: ReactNode; }) => {
    return (
        <div className="p-6 overflow-y-auto">
            {children}
        </div>
    );
};

const PaneFooter = ({children}: { children: ReactNode; }) => {
    return (
        <div className="mt-4 px-6 pb-6 flex justify-end">
            {children}
        </div>
    );
};

const paneLeaveDuration = 200;

export {
    Pane,
    PaneHeader,
    PaneBody,
    PaneFooter,
    paneLeaveDuration,
};
