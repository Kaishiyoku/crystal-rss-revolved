import {useFetcher, useLoaderData} from 'react-router-dom';
import UsersLoaderType from '@/V2/types/UsersLoaderType';
import Table from '@/Components/Table';
import {useLaravelReactI18n} from 'laravel-react-i18n';
import formatDateTime from '@/Utils/formatDateTime';
import {DangerButton} from '@/Components/Button';
import useAuth from '@/V2/Hooks/useAuth';
import UserWithFeedsAndUnreadFeedItemsCount from '@/types/generated/Models/UserWithFeedsAndUnreadFeedItemsCount';

export default function UsersIndexPage() {
    const {t} = useLaravelReactI18n();
    const fetcher = useFetcher({key: 'delete'});
    const {user: authUser} = useAuth();
    const {users} = useLoaderData() as UsersLoaderType;

    const handleDelete = (user: UserWithFeedsAndUnreadFeedItemsCount) => () => {
        fetcher.submit({intent: 'delete', userId: user.id}, {method: 'delete', action: '/admin/users', fetcherKey: 'delete'});
    };

    return (
        <Table>
            <thead>
                <Table.HeadingRow>
                    <Table.HeadingCell>
                        <span className="hidden lg:inline">{t('validation.attributes.name')}</span>
                        <span className="lg:hidden">{t('Users')}</span>
                    </Table.HeadingCell>
                    <Table.HeadingCell hideOnMobile>{t('validation.attributes.email_verified_at')}</Table.HeadingCell>
                    <Table.HeadingCell hideOnMobile>{t('validation.attributes.is_admin')}</Table.HeadingCell>
                    <Table.HeadingCell hideOnMobile>{t('Number of feeds')}</Table.HeadingCell>
                    <Table.HeadingCell hideOnMobile>{t('Number of unread feed items')}</Table.HeadingCell>
                    <Table.HeadingCell hideOnMobile><span className="sr-only">{t('Actions')}</span></Table.HeadingCell>
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
                                <Table.MobileText label={t('validation.attributes.email_verified_at')}>
                                    {user.email_verified_at ? formatDateTime(user.email_verified_at) : '/'}
                                </Table.MobileText>

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

                            <div className="lg:hidden pt-4">
                                {user.id !== authUser?.id && (
                                    <DangerButton
                                        confirmTitle={t('Do you really want to delete the user “:name”?', {name: user.name})}
                                        confirmSubmitTitle={t('Delete user')}
                                        confirmCancelTitle={t('Cancel')}
                                        onClick={handleDelete(user)}
                                    >
                                        {t('Delete')}
                                    </DangerButton>
                                )}
                            </div>
                        </Table.Cell>
                        <Table.Cell
                            hideOnMobile>{user.email_verified_at ? formatDateTime(user.email_verified_at) : '/'}</Table.Cell>
                        <Table.Cell hideOnMobile>{user.is_admin ? t('Yes') : t('No')}</Table.Cell>
                        <Table.Cell hideOnMobile>{user.feeds_count}</Table.Cell>
                        <Table.Cell hideOnMobile>{user.unread_feed_items_count}</Table.Cell>
                        <Table.Cell hideOnMobile>
                            {user.id !== authUser?.id && (
                                <DangerButton
                                    confirmTitle={t('Do you really want to delete the user “:name”?', {name: user.name})}
                                    confirmSubmitTitle={t('Delete user')}
                                    confirmCancelTitle={t('Cancel')}
                                    onClick={handleDelete(user)}
                                >
                                    {t('Delete')}
                                </DangerButton>
                            )}
                        </Table.Cell>
                    </Table.Row>
                ))}
            </tbody>
        </Table>
    );
}
