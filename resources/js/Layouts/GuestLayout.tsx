import ApplicationLogo from '@/Components/ApplicationLogo';
import {Link} from '@inertiajs/react';
import Card from '@/Components/Card';
import {ReactNode} from 'react';

export default function Guest({children}: {children: ReactNode; }) {
    return (
        <div className="min-h-screen flex flex-col sm:justify-center items-center px-4 sm:px-6 lg:px-8 py-12 bg-gray-100 dark:bg-gray-900">
            <div className="mb-8">
                <Link href="/">
                    <ApplicationLogo className="w-20 h-20 fill-current text-gray-500"/>
                </Link>
            </div>

            <Card className="w-full sm:max-w-md">
                <Card.Body>
                    {children}
                </Card.Body>
            </Card>
        </div>
    );
}
