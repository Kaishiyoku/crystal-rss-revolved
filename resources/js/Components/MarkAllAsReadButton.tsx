import {EyeIcon} from '@heroicons/react/20/solid';
import {Button} from '@/Components/Button';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {useState} from 'react';
import ConfirmAlert from '@/Components/ConfirmAlert';
import {router} from '@inertiajs/react';

export default function MarkAllAsReadButton() {
    const {t} = useLaravelReactI18n();

    const [isConfirmAlertOpen, setIsConfirmAlertOpen] = useState(false);

    const markAllAsRead = async () => {
        await window.ky.put(route('mark-all-as-read'));

        router.get(route('dashboard'));
    };

    return (
        <>
            <Button
                onClick={() => setIsConfirmAlertOpen(true)}
                plain
            >
                <EyeIcon/>
                {t('Mark all as read')}
            </Button>

            <ConfirmAlert
                open={isConfirmAlertOpen}
                title={t('Do you really want to mark all articles as read?')}
                confirmTitle={t('Mark all articles as read')}
                onClose={() => setIsConfirmAlertOpen(false)}
                onConfirm={markAllAsRead}
            />
        </>
    );
}
