require('./bootstrap');

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.store('toasts', {
    animationDuration: 200,
    list: [],
    add(message, duration = 2000, type = 'info') {
        const totalDuration = this.list.reduce((accum, toast) => accum + this.animationDuration + toast.duration, 0) + duration;

        this.list.push({
            id: Date.now(),
            message,
            type,
            duration,
            visible: false,
        });

        setTimeout(() => {
            this.list[this.list.length - 1].visible = true;
        }, this.animationDuration);

        setTimeout(() => {
            this.removeOldest();
        }, totalDuration);
    },
    remove(id) {
        const index = this.list.findIndex((toast) => toast.id === id);

        if (index < 0) {
            return;
        }

        this.list[index].visible = false;

        setTimeout(() => {
            this.list = this.list.filter((toast, i) => i !== index);
        }, this.animationDuration);
    },
    removeOldest() {
        if (!this.list[0]) {
            return;
        }

        this.list[0].visible = false;

        setTimeout(() => {
            const [, ...otherToasts] = this.list;
            this.list = otherToasts;
        }, this.animationDuration);
    },
});

Alpine.start();
