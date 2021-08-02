<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="text-4xl px-4 sm:px-0 pb-12">{{ __('Screenshots') }}</div>

    <div class="md:grid xl:grid-cols-3 md:grid-cols-2 md:gap-4">
        <x-card.card class="p-4 mb-8">
            <div class="text-2xl pb-4">{{ __('Add feed') }}</div>

            <img src="{{ asset('img/screenshots/desktop/add_feed.png') }}" alt="{{ __('Add feed') }}" class="hidden md:block" data-theme="light" data-provide="zoomable"/>
            <img src="{{ asset('img/screenshots/mobile/add_feed.png') }}" alt="{{ __('Add feed') }}" class="md:hidden" data-theme="light" data-provide="zoomable"/>

            <img src="{{ asset('img/screenshots/desktop/add_feed_dark.png') }}" alt="{{ __('Add feed') }}" class="hidden md:block" data-theme="dark" data-provide="zoomable"/>
            <img src="{{ asset('img/screenshots/mobile/add_feed_dark.png') }}" alt="{{ __('Add feed') }}" class="md:hidden" data-theme="dark" data-provide="zoomable"/>
        </x-card.card>

        <x-card.card class="p-4 mb-8">
            <div class="text-2xl pb-4">{{ __('Feeds') }}</div>

            <img src="{{ asset('img/screenshots/desktop/feeds.png') }}" alt="{{ __('Feeds') }}" class="hidden md:block" data-theme="light" data-provide="zoomable"/>
            <img src="{{ asset('img/screenshots/mobile/feeds.png') }}" alt="{{ __('Feeds') }}" class="md:hidden" data-theme="light" data-provide="zoomable"/>

            <img src="{{ asset('img/screenshots/desktop/feeds_dark.png') }}" alt="{{ __('Feeds') }}" class="hidden md:block" data-theme="dark" data-provide="zoomable"/>
            <img src="{{ asset('img/screenshots/mobile/feeds_dark.png') }}" alt="{{ __('Feeds') }}" class="md:hidden" data-theme="dark" data-provide="zoomable"/>
        </x-card.card>

        <x-card.card class="p-4 mb-8">
            <div class="text-2xl pb-4">{{ __('Dashboard') }}</div>

            <img src="{{ asset('img/screenshots/desktop/dashboard.png') }}" alt="{{ __('Dashboard') }}" class="hidden md:block" data-theme="light" data-provide="zoomable"/>
            <img src="{{ asset('img/screenshots/mobile/dashboard.png') }}" alt="{{ __('Dashboard') }}" class="md:hidden" data-theme="light" data-provide="zoomable"/>

            <img src="{{ asset('img/screenshots/desktop/dashboard_dark.png') }}" alt="{{ __('Dashboard') }}" class="hidden md:block" data-theme="dark" data-provide="zoomable"/>
            <img src="{{ asset('img/screenshots/mobile/dashboard_dark.png') }}" alt="{{ __('Dashboard') }}" class="md:hidden" data-theme="dark" data-provide="zoomable"/>
        </x-card.card>
    </div>
</div>

<script>
    function changeVisiblityOfThemedElements(isDarkModeEnabled) {
        if (isDarkModeEnabled) {
            document.querySelectorAll('[data-theme="light"]').forEach((element) => {
                element.style.display = 'none';
            });

            document.querySelectorAll('[data-theme="dark"]').forEach((element) => {
                element.style.display = null;
            });
        } else {
            document.querySelectorAll('[data-theme="dark"]').forEach((element) => {
                element.style.display = 'none';
            });

            document.querySelectorAll('[data-theme="light"]').forEach((element) => {
                element.style.display = null;
            });
        }
    }

    changeVisiblityOfThemedElements(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);

    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', (event) => {
        changeVisiblityOfThemedElements(event.matches);
    });
</script>
