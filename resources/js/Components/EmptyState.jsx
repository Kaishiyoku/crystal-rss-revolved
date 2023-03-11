export default function EmptyState({icon: Icon, message, description}) {
    return (
        <div className="flex flex-col items-center">
            <Icon className="w-12 h-12 text-gray-300"/>

            <div className="mt-2 font-semibold">{message}</div>

            <div className="mt-1 text-sm text-muted">{description}</div>
        </div>
    );
}
