import {FunctionComponent, ReactNode} from 'react';
import {IconProps} from '@/types';

export default function EmptyState({icon: Icon, message, description, children}: { icon: FunctionComponent<IconProps>; message: string; description: string; children?: ReactNode; }) {
    return (
        <div className="flex flex-col items-center">
            <div className="flex flex-col items-center max-w-sm">
                <Icon className="w-24 h-24 text-gray-400"/>

                <div className="mt-2 font-semibold text-xl text-center">{message}</div>

                <div className="mt-1 text-center text-muted">{description}</div>

                {children}
            </div>
        </div>
    );
}
