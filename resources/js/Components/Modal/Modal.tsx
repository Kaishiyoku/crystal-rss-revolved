import {Fragment, ReactNode, useEffect} from 'react';
import {Dialog, Transition} from '@headlessui/react';

const Modal = (
    {
        children,
        appear = false,
        show = false,
        maxWidth = '2xl',
        closeable = true,
        onClose = () => {},
    }: {
        children: ReactNode;
        appear?: boolean;
        show?: boolean;
        maxWidth?: 'sm' | 'md' | 'lg' | 'xl' | '2xl';
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

    const maxWidthClass = {
        sm: 'sm:max-w-sm',
        md: 'sm:max-w-md',
        lg: 'sm:max-w-lg',
        xl: 'sm:max-w-xl',
        '2xl': 'sm:max-w-2xl',
    }[maxWidth];

    return (
        <Transition appear={appear} show={show} as={Fragment} leave="duration-200">
            <Dialog
                as="div"
                id="modal"
                className="fixed inset-0 flex max-h-full px-4 py-6 sm:px-0 items-center z-50 transform transition-all backdrop-blur"
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
                    enterFrom="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    enterTo="opacity-100 translate-y-0 sm:scale-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100 translate-y-0 sm:scale-100"
                    leaveTo="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                >
                    <Dialog.Panel
                        className={`flex flex-col mb-4 bg-white dark:bg-gray-800 rounded-lg max-h-full shadow-xl transform transition-all w-full sm:mx-auto ${maxWidthClass}`}
                    >
                        {children}
                    </Dialog.Panel>
                </Transition.Child>
            </Dialog>
        </Transition>
    );
};

const ModalHeader = ({children}: { children: ReactNode; }) => {
    return (
        <div className="p-6">
            <h2 className="text-xl font-semibold text-gray-900 dark:text-gray-100 pr-8">
                {children}
            </h2>
        </div>
    );
};

const ModalBody = ({children}: { children: ReactNode; }) => {
    return (
        <div className="p-6 overflow-y-auto">
            {children}
        </div>
    );
};

const ModalFooter = ({children}: { children: ReactNode; }) => {
    return (
        <div className="mt-4 px-6 pb-6 flex justify-end">
            {children}
        </div>
    );
};

const modalLeaveDuration = 200;

export {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
    modalLeaveDuration,
};
