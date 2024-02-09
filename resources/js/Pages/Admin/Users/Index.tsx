import {Head, useForm} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {PageProps} from '@/types';
import User from '@/types/Models/User';
import Table from '@/Components/Table';
import Card from '@/Components/Card';
import formatDateTime from '@/Utils/formatDateTime';
import {DangerButton} from '@/Components/Button';
import {RouteParams} from 'ziggy-js';

type UserWithStats = User & {
    feeds_count: number;
    unread_feed_items_count: number;
};

export default function Index({users, ...props}: PageProps & { users: UserWithStats[]; }) {
    const {t} = useLaravelReactI18n();
    const {delete: destroy, processing: deleteProcessing} = useForm();

    const handleDelete = (user: UserWithStats) => () => {
        destroy(route('admin.users.destroy', user as unknown as RouteParams<'admin.users.destroy'>));
    };

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Manage users')}</Header>}
        >
            <Head title={t('Manage users')}/>

            <Card>
                <Table>
                    <thead>
                        <Table.HeadingRow>
                            <Table.HeadingCell>{t('validation.attributes.name')}</Table.HeadingCell>
                            <Table.HeadingCell>{t('validation.attributes.email_verified_at')}</Table.HeadingCell>
                            <Table.HeadingCell hideOnMobile>{t('validation.attributes.is_admin')}</Table.HeadingCell>
                            <Table.HeadingCell hideOnMobile>{t('Number of feeds')}</Table.HeadingCell>
                            <Table.HeadingCell hideOnMobile>{t('Number of unread feed items')}</Table.HeadingCell>
                            <Table.HeadingCell><span className="sr-only">{t('Actions')}</span></Table.HeadingCell>
                        </Table.HeadingRow>
                    </thead>

                    <tbody>
                        {users.map((user) => (
                            <Table.Row key={user.id}>
                                <Table.Cell highlighted>
                                    <div className="flex space-x-1">
                                        <div className="text-muted">
                                            <span className="select-none">#</span>
                                            <span>{user.id}</span>
                                        </div>

                                        <div>{user.name}</div>
                                    </div>

                                    <Table.MobileContainer>
                                        <Table.MobileText label={t('validation.attributes.is_admin')}>
                                            {user.is_admin ? t('Yes') : t('No')}
                                        </Table.MobileText>

                                        <Table.MobileText label={t('Number of feeds')}>
                                            {user.feeds_count}
                                        </Table.MobileText>

                                        <Table.MobileText label={t('Number of unread feed items')}>
                                            {user.unread_feed_items_count}
                                        </Table.MobileText>
                                    </Table.MobileContainer>
                                </Table.Cell>
                                <Table.Cell>{user.email_verified_at ? formatDateTime(user.email_verified_at) : '/'}</Table.Cell>
                                <Table.Cell hideOnMobile>{user.is_admin ? t('Yes') : t('No')}</Table.Cell>
                                <Table.Cell hideOnMobile>{user.feeds_count}</Table.Cell>
                                <Table.Cell hideOnMobile>{user.unread_feed_items_count}</Table.Cell>
                                <Table.Cell>
                                    {user.id !== props.auth.user.id && (
                                        <DangerButton
                                            confirmTitle={t('Do you really want to delete the user “:name”?', {name: user.name})}
                                            confirmSubmitTitle={t('Delete user')}
                                            confirmCancelTitle={t('Cancel')}
                                            onClick={handleDelete(user)}
                                            disabled={deleteProcessing}
                                        >
                                            {t('Delete')}
                                        </DangerButton>
                                    )}
                                </Table.Cell>
                            </Table.Row>
                        ))}
                    </tbody>
                </Table>
            </Card>
        </AuthenticatedLayout>
    );
}
