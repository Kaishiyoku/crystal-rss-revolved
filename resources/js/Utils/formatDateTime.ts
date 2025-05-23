import { format, parseISO } from 'date-fns';
import { useLaravelReactI18n } from 'laravel-react-i18n';

export default function formatDateTime(dateStr: string): string {
	const { currentLocale } = useLaravelReactI18n();

	const localizedDateTimeFormats: { [key: string]: string } = {
		en: 'dd/MM/yyyy HH:mm',
		de: 'dd.MM.yyyy HH:mm',
	};

	return format(parseISO(dateStr), localizedDateTimeFormats[currentLocale()]);
}
