import {Modal, ModalFooter, ModalHeader} from '@/Components/Modal/Modal';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {DangerButton, SecondaryButton} from '@/Components/Button';

export default function ConfirmModal({show, onClose, onConfirm}) {
    const {t} = useLaravelReactI18n();

    return (
        <Modal show={show} onClose={onClose} maxWidth="sm">
            <ModalHeader>
                {t('Are you sure?')}
            </ModalHeader>

            <ModalFooter>
                <SecondaryButton onClick={onClose}>
                    {t('No')}
                </SecondaryButton>

                <DangerButton confirm={false} onClick={onConfirm} className="ml-3">
                    {t('Yes')}
                </DangerButton>
            </ModalFooter>
        </Modal>
    );
}
