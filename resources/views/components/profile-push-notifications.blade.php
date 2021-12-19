<x-jet-action-section>
    <x-slot name="title">
        {{ __('Push Notifications') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Enable push notifications.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600 dark:text-gray-500">
            {{ __('You can enable push notificiations. Every time new articles have been found you will receive a notification.') }}
        </div>

        <div x-data>
            <div class="mt-5">
                <template x-if="hasPushNotificationsEnabled()">
                    <div>
                        <div class="max-w-xl text-sm text-green-500 font-semibold">
                            {{ __('Push notifications enabled.') }}
                        </div>

                        <x-jet-secondary-button class="mt-5" @click="axios.post('{{ route('home.send_test_notification') }}')">
                            {{ __('Send test notification') }}
                        </x-jet-secondary-button>
                    </div>
                </template>

                <template x-if="!hasPushNotificationsEnabled()">
                    <div>
                        <x-jet-button @click="requestPushNotificationPermission(() => window.location.reload(), () => window.location.reload())" ::disabled="getNativePushNotificationPermissionLevel() === 'denied'">
                            {{ __('Enable push notifications') }}
                        </x-jet-button>

                        <div class="pt-6 max-w-xl text-sm text-red-600 dark:text-red-500" x-show-="getNativePushNotificationPermissionLevel() === 'denied'">
                            {{ __('Permission for notifications has been denied. Please adjust the permission in the browser settings if you want to re-enable it.') }}
                        </div>
                    </div>
                </template>
            </div>
        </div>

        @push('scripts')
            <script type="text/javascript">
                onDomReady(() => {
                    Echo.private(`test-notification.${userId}`)
                        .listen('TestNotificationSent', ({title, message}) => {
                            Alpine.store('toasts').add(message, 10000);
                            sendPushNotification(title, message, 10000, () => window.location.reload());
                        });
                });
            </script>
        @endpush
    </x-slot>
</x-jet-action-section>
