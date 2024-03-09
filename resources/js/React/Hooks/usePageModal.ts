import {useLocation, useNavigate} from 'react-router-dom';
import {useEffect, useState} from 'react';
import ValidationErrors from '@/React/types/ValidationErrors';
import {modalLeaveDuration} from '@/Components/Modal/Modal';
import wait from '@/React/Utils/wait';

export default function usePageModal(errors: ValidationErrors | null, to: string) {
    const [show, setShow] = useState(true);
    const navigate = useNavigate();
    const location = useLocation();

    useEffect(() => {
        if (errors !== null) {
            return;
        }

        setShow(false);
    }, [errors]);

    useEffect(() => {
        if (!show) {
            void wait(modalLeaveDuration).then(() => navigate(to));
        }
    }, [show]);

    useEffect(() => {
        setShow(true);
    }, [location]);

    const handleClose = () => setShow(false);

    return {
        show,
        handleClose,
    };
}
