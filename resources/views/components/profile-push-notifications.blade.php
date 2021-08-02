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
                    <div class="max-w-xl text-sm text-green-500 font-semibold">
                        {{ __('Push notifications enabled.') }}
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
    </x-slot>
</x-jet-action-section>
