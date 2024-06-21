import {ReactNode} from 'react';
import {Link} from '@inertiajs/react';

export function LinkStack({children}: { children: ReactNode; }) {
    return (
        <div>
            {children}
        </div>
    );
}

export function LinkStackItem({image, url, title, children}: { image?: ReactNode; url: string; title: string; children?: ReactNode; }) {
    return (
        <Link href={url} className="block px-2 py-2.5 hover:bg-gray-50 dark:hover:bg-gray-800/50 first:rounded-t-md last:rounded-b-md transition">
            {image
                ? (
                    <div className="flex space-x-2">
                        {image}
                        <div className="font-semibold">{title}</div>
                    </div>
                )
                : (
                    <div className="font-semibold">{title}</div>
                )
            }

            {children && (
                <div className="text-muted">{children}</div>
            )}
        </Link>
    );
}
