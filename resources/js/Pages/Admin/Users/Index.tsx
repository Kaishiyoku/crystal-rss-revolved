import {Head} from '@inertiajs/react';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout';
import Header from '@/Components/Page/Header';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import {PageProps} from '@/types';
import User from '@/types/Models/User';
import Table from '@/Components/Table';
import Card from '@/Components/Card';
import formatDateTime from '@/Utils/formatDateTime';

export default function Index({users, ...props}: PageProps & { users: (User & {feeds_count: number; unread_feed_items_count: number;})[]; }) {
    const {t} = useLaravelReactI18n();

    return (
        <AuthenticatedLayout
            auth={props.auth}
            errors={props.errors}
            header={<Header>{t('Users')}</Header>}
        >
            <Head title={t('Users')}/>

            <Card>
                <Table>
                    <thead>
                        <Table.HeadingRow>
                            <Table.HeadingCell>{t('validation.attributes.name')}</Table.HeadingCell>
                            <Table.HeadingCell>{t('validation.attributes.email_verified_at')}</Table.HeadingCell>
                            <Table.HeadingCell hideOnMobile>{t('validation.attributes.is_admin')}</Table.HeadingCell>
                            <Table.HeadingCell hideOnMobile>{t('Number of feeds')}</Table.HeadingCell>
                            <Table.HeadingCell hideOnMobile>{t('Number of unread feed items')}</Table.HeadingCell>
                        </Table.HeadingRow>
                    </thead>

                    <tbody>
                        {users.map((user) => (
                            <Table.Row key={user.id}>
                                <Table.Cell highlighted>
                                    {user.name}

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
                            </Table.Row>
                        ))}
                    </tbody>
                </Table>
            </Card>
        </AuthenticatedLayout>
    );
}
