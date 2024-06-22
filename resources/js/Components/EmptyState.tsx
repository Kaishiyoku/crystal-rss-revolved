import React, {ReactNode} from 'react';

export function EmptyState({icon: Icon, message, description, children}: { icon: React.ForwardRefExoticComponent<React.PropsWithoutRef<React.SVGProps<SVGSVGElement>> & { title?: string; titleId?: string; } & React.RefAttributes<SVGSVGElement>>; message: string; description: string; children?: ReactNode; }) {
    return (
        <div className="flex flex-col items-center">
            <div className="flex flex-col items-center max-w-sm">
                <Icon className="size-14 text-gray-400"/>

                <h3 className="mt-2 font-semibold text-lg text-center">{message}</h3>

                <div className="mt-1 text-center text-muted">{description}</div>

                {children && (
                    <div className="pt-4">
                        {children}
                    </div>
                )}
            </div>
        </div>
    );
}
