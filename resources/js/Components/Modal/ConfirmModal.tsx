import {Modal, ModalFooter, ModalHeader} from '@/Components/Modal/Modal';
import {DangerButton, SecondaryButton} from '@/Components/Button';
import React from 'react';

type ConfirmModalProps = {
    show: boolean;
    title: string;
    submitTitle: string;
    cancelTitle: string;
    onClose: () => void;
    onConfirm: (event: React.MouseEvent<HTMLButtonElement>) => void;
};

export default function ConfirmModal({show, title, submitTitle, cancelTitle, onClose, onConfirm}: ConfirmModalProps) {
    return (
        <Modal show={show} onClose={onClose} maxWidth="sm">
            <ModalHeader>
                {title}
            </ModalHeader>

            <ModalFooter>
                <SecondaryButton onClick={onClose}>
                    {cancelTitle}
                </SecondaryButton>

                <DangerButton confirm={false} onClick={onConfirm} className="ml-3">
                    {submitTitle}
                </DangerButton>
            </ModalFooter>
        </Modal>
    );
}
