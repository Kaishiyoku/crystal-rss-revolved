import {Fragment, useEffect} from 'react';
import {Dialog, Transition} from '@headlessui/react';

const Modal = ({children, show = false, maxWidth = '2xl', closeable = true, onClose = () => {}}) => {
    useEffect(() => {
        document.body.style.overflowY = show ? 'hidden' : null;
    }, [show]);

    const close = () => {
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
        <Transition show={show} as={Fragment} leave="duration-200">
            <Dialog
                as="div"
                id="modal"
                className="fixed inset-0 flex overflow-y-auto px-4 py-6 sm:px-0 items-center z-50 transform transition-all"
                onClose={close}
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
                        className={`mb-4 bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-xl transform transition-all w-full sm:mx-auto ${maxWidthClass}`}
                    >
                        {children}
                    </Dialog.Panel>
                </Transition.Child>
            </Dialog>
        </Transition>
    );
};

const ModalHeader = ({children}) => {
    return (
        <div className="px-4 pt-4">
            <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                {children}
            </h2>
        </div>
    );
};

const ModalBody = ({children}) => {
    return (
        <div className="p-4">
            {children}
        </div>
    );
};

const ModalFooter = ({children}) => {
    return (
        <div className="mt-4 px-4 pb-4 flex justify-end">
            {children}
        </div>
    );
};

export {
    Modal,
    ModalHeader,
    ModalBody,
    ModalFooter,
};
