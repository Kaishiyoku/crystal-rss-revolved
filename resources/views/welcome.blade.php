<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">

        <!-- Scripts -->
        <script src="{{ mix('js/welcome.js') }}" defer></script>

        @include('shared._favicon')
    </head>
    <body class="antialiased bg-gray-100 dark:bg-gray-900 pb-4 dark:text-gray-100">
        <div class="absolute w-full bg-gradient-to-r lg:bg-none from-[#6927ff] to-[#914cd9]">
            <div class="md:flex md:justify-between">
                <div class="text-white uppercase text-2xl pl-8 pt-4 tracking-wider">
                    <a href="{{ route('welcome') }}">{{ config('app.name', 'Laravel') }}</a>
                </div>

                @if (Route::has('login'))
                    <div id="navigation" class="md:fixed md:top-0 md:right-0 px-4 md:px-6 py-4 rounded-bl-full transition ease-out duration-500">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-purple-300 hover:text-white uppercase pl-4 transition-all duration-200 tracking-wider">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="text-purple-300 hover:text-white uppercase pl-4 transition-all duration-200 tracking-wider">{{ __('Log in') }}</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-purple-300 hover:text-white uppercase pl-4 transition-all duration-200 tracking-wider">{{ __('Register') }}</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <x-header-background class="absolute mt-28 md:mt-16 lg:mt-4 opacity-75"/>
        <x-header-background class="absolute mt-24 md:mt-12 lg:mt-0"/>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 pt-48 sm:pt-56 md:pt-64 lg:pt-80">
            <div class="flex flex-col justify-center items-center pt-8 sm:pt-0">
                <x-application-authentication-logo class="w-32 h-40" />

                <span class="text-6xl md:text-8xl text-center pt-4">
                    {{ config('app.name', 'Laravel') }}
                </span>
            </div>
        </div>

        @if (config('app.name'))
            <div class="max-w-xl mx-auto mt-16 px-4 sm:px-0 py-4">
                <p class="text-2xl pb-8">
                    {{ __('welcome_page_headline', ['name' => config('app.name')]) }}
                </p>

                <p class="text-xl pb-4">
                    {{ __('welcome_page_text_1') }}
                </p>

                <p class="text-xl pb-4">
                    {{ __('welcome_page_text_2') }}
                </p>

                <p class="text-xl">
                    {{ __('welcome_page_text_3') }}
                </p>

                <div class="mt-16 space-x-4 text-xl">
                    @if (config('app.contact_email'))
                        <a href="mailto:{{ config('app.contact_email') }}" class="text-[#914cd9] dark:text-[#6927ff] hover:underline">
                            {{ __('Contact') }}
                        </a>
                    @endif

                    @if (config('app.github_url'))
                        <a href="{{ config('app.github_url') }}" class="text-[#914cd9] dark:text-[#6927ff] hover:underline">
                            {{ __('GitHub') }}
                        </a>
                    @endif
                </div>
            </div>
        @endif

        <button type="button" class="block mx-auto mt-12 text-[#914cd9] dark:text-[#6927ff] opacity-50 hover:opacity-75 focus:opacity-90 transition" data-scroll="#screenshots">
            <x-heroicon-s-arrow-circle-down class="w-16 h-16"/>
        </button>

        <div id="screenshots" class="mt-64 pt-4">
            @include('shared._screenshots')
        </div>
    </body>
</html>
