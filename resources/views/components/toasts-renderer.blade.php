@props(['position' => 'top right'])

<div x-data class="{{ classNames('fixed p-4 overflow-x-hidden', [
    'top-0 right-0' => $position === 'top right',
    'bottom-0 right-0' => $position === 'bottom right',
    'top-0 left-0' => $position === 'top left',
    'bottom-0 left-0' => $position === 'bottom left',
]) }}">
    <template
        x-for="toast in $store.toasts.list"
        :key="toast.id"
    >
        <div
            x-show="toast.visible"
            @click="$store.toasts.remove(toast.id)"
            x-transition:enter="transition ease-in duration-200"
            x-transition:enter-start="opacity-0 translate-y-2"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-out duration-500"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="bg-gray-900 bg-gradient-to-r text-white p-3 rounded mb-3 shadow-lg flex items-center cursor-pointer"
            :class="{
				'from-primary-500 to-primary-600': toast.type === 'info',
				'from-green-500 to-green-600': toast.type === 'success',
				'from-yellow-400 to-yellow-500': toast.type === 'warning',
				'from-red-500 to-secondary-500': toast.type === 'error'
				}"
        >
            <div x-text="toast.message"></div>
        </div>
    </template>
</div>
