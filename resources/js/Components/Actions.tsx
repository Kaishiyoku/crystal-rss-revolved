import clsx from 'clsx';
import {Children, cloneElement, Fragment, ReactNode, useState} from 'react';
import {Transition} from '@headlessui/react';
import {HeadlessButton, SecondaryButton} from '@/Components/Button.jsx';
import {is} from 'ramda';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import XMarkOutlineIcon from '@/Icons/XMarkOutlineIcon';
import EllipsisVerticalOutlineIcon from '@/Icons/EllipsisVerticalOutlineIcon';

const MobileActions = ({children}: { children: ReactNode; }) => {
    const {t} = useLaravelReactI18n();
    const [show, setShow] = useState(false);

    const adjustedChildren = Children.map(children, (child) => {
        if (!is(Object, child)) {
            return child;
        }

        // @ts-expect-error it doesn't matter what type child is because it must be some kind of clonable element
        return cloneElement(child, {className: clsx(child.props.className, 'px-4 py-2 rounded-lg hover:bg-gray-200 dark:hover:bg-gray-700 transition')});
    });

    return (
        <div className="sm:hidden">
            <div className="pb-8">
                <SecondaryButton
                    onClick={() => setShow(true)}
                    className="w-full"
                >
                    <span>{t('Actions')}</span>

                    <EllipsisVerticalOutlineIcon className="w-5 h-5"/>
                </SecondaryButton>
            </div>

            <Transition show={show} as="div" leave="duration-200">
                <Transition.Child
                    as="div"
                    enter="ease-out duration-300"
                    enterFrom="opacity-0"
                    enterTo="opacity-100"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100"
                    leaveTo="opacity-0"
                    className="fixed z-10 inset-0 bg-gray-500/75 dark:bg-gray-900/75"
                    onClick={() => setShow(false)}
                />

                <Transition.Child
                    as={Fragment}
                    enter="ease-out duration-300"
                    enterFrom="opacity-0 translate-y-4"
                    enterTo="opacity-100 translate-y-0"
                    leave="ease-in duration-200"
                    leaveFrom="opacity-100 translate-y-0"
                    leaveTo="opacity-0 translate-y-4"
                >
                    <div className="fixed z-20 bottom-0 left-0 right-0 p-1 rounded-t-lg bg-white dark:bg-gray-800">
                        <div className="flex justify-between items-center px-3.5 pt-3.5 pb-2 font-bold text-lg">
                            <div>{t('Actions')}</div>

                            <HeadlessButton
                                onClick={() => setShow(false)}
                                className="button-icon"
                            >
                                <XMarkOutlineIcon className="w-5 h-5"/>
                            </HeadlessButton>
                        </div>

                        <div className="flex flex-col max-h-[350px] p-3.5 overflow-y-auto scrollbar-y-sm">
                            {adjustedChildren}
                        </div>
                    </div>
                </Transition.Child>
            </Transition>
        </div>
    );
};

export default function Actions({className = '', children}: { className?: string; children: ReactNode; }) {
    if (!children) {
        return null;
    }

    return (
        <>
            <div className={clsx('hidden sm:flex justify-end pb-5 space-x-4', className)}>
                {children}
            </div>

            <MobileActions>
                {children}
            </MobileActions>
        </>
    );
}
