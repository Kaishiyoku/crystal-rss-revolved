export default function EmptyState({icon: Icon, message, description, children}) {
    return (
        <div className="flex flex-col items-center">
            <div className="flex flex-col items-center max-w-sm">
                <Icon className="w-12 h-12 text-gray-300"/>

                <div className="mt-2 font-semibold text-center">{message}</div>

                <div className="mt-1 text-sm text-center text-muted">{description}</div>

                {children}
            </div>
        </div>
    );
}
