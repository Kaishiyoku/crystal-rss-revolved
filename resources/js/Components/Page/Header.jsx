export default function Header({subTitle = null, children}) {
    return (
        <h2 className="font-semibold text-gray-800 dark:text-gray-300 leading-tight">
            {children}

            {subTitle && (
                <span className="ml-2 text-sm text-muted">
                    {subTitle}
                </span>
            )}
        </h2>
    );
}
