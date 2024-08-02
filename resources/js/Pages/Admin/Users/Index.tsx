import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {PageProps} from '@/types';
import formatDateTime from '@/Utils/formatDateTime';
import UserWithStats from '@/types/generated/Models/UserWithStats';
import DeleteUserButton from '@/Pages/Admin/Users/Partials/DeleteUserButton';
import {
    Table,
    TableBody,
    TableCell,
    TableHead,
    TableHeader,
    TableMobileContainer,
    TableMobileText,
    TableRow,
} from '@/Components/Table';

export default function Index({users, ...props}: PageProps & { users: UserWithStats[]; }) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={t('Manage users')}
        >
            <Head title={t('Manage users')}/>

            <Table>
                <TableHead className="hidden md:table-header-group">
                    <TableRow>
                        <TableHeader>{t('validation.attributes.name')}</TableHeader>
                        <TableHeader className="hidden md:table-cell">{t('validation.attributes.email_verified_at')}</TableHeader>
                        <TableHeader className="hidden md:table-cell">{t('validation.attributes.is_admin')}</TableHeader>
                        <TableHeader className="hidden md:table-cell">{t('Number of feeds')}</TableHeader>
                        <TableHeader className="hidden md:table-cell">{t('Number of unread feed items')}</TableHeader>
                        <TableHeader className="hidden md:table-cell"><span className="sr-only">{t('Actions')}</span></TableHeader>
                    </TableRow>
                </TableHead>

                <TableBody>
                    {users.map((user) => (
                        <TableRow key={user.id}>
                            <TableCell>
                                <div className="flex space-x-1 font-semibold text-lg md:text-base">
                                    <div className="text-muted">
                                        <span className="select-none">#</span>
                                        <span>{user.id}</span>
                                    </div>

                                    <div>{user.name}</div>
                                </div>

                                <TableMobileContainer>
                                    <TableMobileText label={t('validation.attributes.email_verified_at')}>
                                        {user.email_verified_at ? formatDateTime(user.email_verified_at) : '/'}
                                    </TableMobileText>

                                    <TableMobileText label={t('validation.attributes.is_admin')}>
                                        {user.is_admin ? t('Yes') : t('No')}
                                    </TableMobileText>

                                    <TableMobileText label={t('Number of feeds')}>
                                        {user.feeds_count}
                                    </TableMobileText>

                                    <TableMobileText label={t('Number of unread feed items')}>
                                        {user.unread_feed_items_count}
                                    </TableMobileText>
                                </TableMobileContainer>

                                <div className="lg:hidden pt-4">
                                    {user.id !== props.auth.user.id && (
                                        <DeleteUserButton user={user}/>
                                    )}
                                </div>
                            </TableCell>
                            <TableCell className="hidden md:table-cell">{user.email_verified_at ? formatDateTime(user.email_verified_at) : '/'}</TableCell>
                            <TableCell className="hidden md:table-cell">{user.is_admin ? t('Yes') : t('No')}</TableCell>
                            <TableCell className="hidden md:table-cell">{user.feeds_count}</TableCell>
                            <TableCell className="hidden md:table-cell">{user.unread_feed_items_count}</TableCell>
                            <TableCell className="hidden md:table-cell">
                                {user.id !== props.auth.user.id && (
                                    <DeleteUserButton user={user}/>
                                )}
                            </TableCell>
                        </TableRow>
                    ))}
                </TableBody>
            </Table>
        </AuthenticatedLayout>
    );
}
