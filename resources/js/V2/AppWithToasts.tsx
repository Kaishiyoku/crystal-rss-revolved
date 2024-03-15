import {useLaravelReactI18n} from 'laravel-react-i18n';
import {Toast, Toaster} from 'react-hot-toast';
import {Transition} from '@headlessui/react';
import {RouterProvider} from 'react-router-dom';
import router from '@/V2/Core/Router/router';

export default function AppWithToasts() {
    const {t} = useLaravelReactI18n();

    const toastRenderer = (toast: Toast) => {
        return (
            <Transition
                appear
                show={toast.visible}
                as="div"
                enter="ease-out duration-300"
                enterFrom="opacity-0 translate-y-4"
                enterTo="opacity-100 translate-y-0"
                leave="ease-in duration-200"
                leaveFrom="opacity-100 translate-y-0"
                leaveTo="opacity-0 translate-y-4"
                className="px-4 py-3 dark:bg-gray-800 rounded-lg select-none"
            >
                {t(toast.message as string)}
            </Transition>
        );
    };

    return (
        <>
            <Toaster position="bottom-center">
                {toastRenderer}
            </Toaster>

            <RouterProvider router={router}/>
        </>
    );
}
