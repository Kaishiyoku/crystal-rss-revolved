import { useLaravelReactI18n } from 'laravel-react-i18n';
import type { PropsWithoutRef, SVGProps } from 'react';
import { twMerge } from 'tailwind-merge';

export default function LoadingIcon({
	className,
	...props
}: Omit<PropsWithoutRef<SVGProps<SVGSVGElement>>, 'aria-label'>) {
	const { t } = useLaravelReactI18n();

	return (
		<svg
			{...props}
			aria-hidden="true"
			className={twMerge('size-5 animate-spin text-white', className)}
			xmlns="http://www.w3.org/2000/svg"
			fill="none"
			viewBox="0 0 24 24"
			aria-label={t('Loading…')}
			data-slot="icon"
		>
			<circle
				className="opacity-25"
				cx="12"
				cy="12"
				r="10"
				stroke="currentColor"
				strokeWidth="4"
			/>
			<path
				className="opacity-75"
				fill="currentColor"
				d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
			/>
		</svg>
	);
}
