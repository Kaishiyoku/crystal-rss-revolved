<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>
            @isset($pageTitle)
                {{ config('app.name', 'Laravel') }} - {{ $pageTitle }}
            @else
                {{ config('app.name', 'Laravel') }}
            @endisset
        </title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        @livewireStyles

        <!-- Scripts -->
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="{{ mix('js/misc.js') }}"></script>

        @include('shared._favicon')

        <style>
            .custom-theme {
                @foreach (availableThemeColorFields() as $colorField)
                    --{{ Str::replace('_', '-', $colorField) }}: {{ session()->get('theme.' . Str::replace('_', '-', $colorField)) }};
                @endforeach
            }
        </style>
    </head>

    <body class="font-sans antialiased {{ session()->has('theme.custom') ? 'custom-theme' : 'base-theme' }}">
        <x-jet-banner />

        <div class="min-h-screen bg-gray-100 dark:text-gray-500/75 dark:bg-gray-900">
            @livewire('navigation-menu')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-700/25 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <div class="py-12">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                        {{ $slot }}
                    </div>
                </div>
            </main>
        </div>

        <x-toasts-renderer position="bottom right"/>

        @stack('modals')

        @livewireScripts

        <script type="text/javascript">
            const userId = {{ auth()->user()->id }};
        </script>

        @stack('scripts')
    </body>
</html>
