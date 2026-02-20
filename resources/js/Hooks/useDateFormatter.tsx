import { useLaravelReactI18n } from 'laravel-react-i18n';
import { format, parseISO } from 'date-fns';

export function useDateFormatter() {
	const { currentLocale } = useLaravelReactI18n();

	return {
		formatDateTime: formatDateTime(currentLocale()),
	};
}

const formatDateTime =
	(locale: string) =>
	(dateStr: string): string => {
		const localizedDateTimeFormats: { [key: string]: string } = {
			en: 'dd/MM/yyyy HH:mm',
			de: 'dd.MM.yyyy HH:mm',
		};

		return format(parseISO(dateStr), localizedDateTimeFormats[locale]);
	};
