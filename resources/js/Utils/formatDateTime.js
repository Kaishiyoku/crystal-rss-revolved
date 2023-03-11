import {format, parseISO} from 'date-fns';
import {useLaravelReactI18n} from 'laravel-react-i18n';

const localizedDateTimeFormats = {
    en: 'dd/MM/yyyy HH:mm',
    de: 'dd.MM.yyyy HH:mm',
};

/**
 * @param {string} dateStr
 * @returns {string}
 */
export default function formatDateTime(dateStr) {
    const {getActiveLanguage} = useLaravelReactI18n();

    return format(parseISO(dateStr), localizedDateTimeFormats[getActiveLanguage()]);
}
