import {Alert, AlertActions, AlertTitle} from '@/Components/Alert';
import {Button} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';

export default function ConfirmAlert({open, title, confirmTitle, onClose, onConfirm}: { open: boolean; title: string; confirmTitle: string; onClose: (value: boolean) => void; onConfirm: () => void; }) {
    const {t} = useLaravelReactI18n();

    const handleConfirmClick = () => {
        onClose(true);

        onConfirm();
    };

    return (
        <Alert open={open} onClose={onClose}>
            <AlertTitle>
                {title}
            </AlertTitle>

            <AlertActions>
                <Button onClick={() => onClose(false)} plain>
                    {t('Cancel')}
                </Button>

                <Button onClick={handleConfirmClick}>
                    {confirmTitle}
                </Button>
            </AlertActions>
        </Alert>
    );
}
