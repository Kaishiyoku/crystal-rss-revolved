<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;600;700&display=swap">

        @vite(['resources/css/app.css', 'resources/js/welcome.js'])

        @include('shared._favicon')
    </head>
    <body class="antialiased pb-4 dark:text-gray-100 bg-gradient-to-r from-primary-600 to-secondary-600">
        <div class="absolute w-full">
            <div class="md:flex md:justify-between">
                <div class="text-white uppercase text-2xl pl-8 pt-4 tracking-wider">
                    <a href="{{ route('welcome') }}">{{ config('app.name', 'Laravel') }}</a>
                </div>

                @if (Route::has('login'))
                    <div id="navigation" class="md:fixed md:top-0 md:right-0 px-4 md:px-6 py-4 rounded-bl-2xl transition ease-out duration-500">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-primary-200 hover:text-white uppercase transition-all duration-200 tracking-wider">{{ __('Dashboard') }}</a>
                        @else
                            <a href="{{ route('login') }}" class="text-primary-200 hover:text-white uppercase transition-all duration-200 tracking-wider">{{ __('Log in') }}</a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="text-primary-200 hover:text-white uppercase pl-4 transition-all duration-200 tracking-wider">{{ __('Register') }}</a>
                            @endif
                        @endauth
                    </div>
                @endif
            </div>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 pt-48 sm:pt-56 md:pt-64 lg:pt-80">
            <div class="flex flex-col justify-center items-center pt-8 sm:pt-0">
                <div class="flex justify-center items-center bg-white/25 p-2 rounded-full w-40 h-40">
                    <x-application-authentication-logo class="h-full"/>
                </div>

                <div class="text-6xl md:text-8xl text-center pt-8 text-transparent bg-clip-text bg-gradient-to-r from-primary-300 to-secondary-300">
                    {{ config('app.name', 'Laravel') }}
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
                                <x-link href="mailto:{{ config('app.contact_email') }}" class="text-primary-300 dark:text-secondary-400 border-primary-500 dark:border-secondary-500">
                                    {{ __('Contact') }}
                                </x-link>
                            @endif

                            @if (config('app.github_url'))
                                <x-link href="{{ config('app.github_url') }}" class="text-primary-300 dark:text-secondary-400 border-primary-500 dark:border-secondary-500">
                                    {{ __('GitHub') }}
                                </x-link>
                            @endif

                            <x-link href="{{ url('/docs') }}" class="text-primary-300 dark:text-secondary-400 border-primary-500 dark:border-secondary-500">
                                {{ __('API documentation') }}
                            </x-link>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <button type="button" class="block mx-auto mt-12 text-primary-400 dark:text-secondary-400 opacity-50 hover:opacity-75 focus:opacity-90 transition" data-scroll="#screenshots">
            <x-heroicon-s-arrow-circle-down class="w-16 h-16"/>
        </button>

        <div id="screenshots" class="mt-64 pt-4">
            @include('shared._screenshots')
        </div>
    </body>
</html>
