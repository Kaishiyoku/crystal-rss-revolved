import substr from '@/Utils/substr';

const getBrowserLocale = (): string => substr(navigator.language, 0, 2);

export default getBrowserLocale;
