import {DangerButton, SecondaryButton} from '@/Components/Button';
import Modal from '@/Components/Modal';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function ConfirmModal({show, onClose, onConfirm}) {
    const {t} = useLaravelReactI18n();

    return (
        <Modal show={show} onClose={onClose}>
            <div className="p-6">
                <h2 className="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {t('Are you sure?')}
                </h2>

                <div className="mt-6 flex justify-end">
                    <SecondaryButton onClick={onClose}>
                        {t('No')}
                    </SecondaryButton>

                    <DangerButton confirm={false} onClick={onConfirm} className="ml-3">
                        {t('Yes')}
                    </DangerButton>
                </div>
            </div>
        </Modal>
    );
}
