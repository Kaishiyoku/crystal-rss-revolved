const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js('resources/js/app.js', 'public/js')
    .js('resources/js/misc.js', 'public/js')
    .js('resources/js/welcome.js', 'public/js')
    .postCss('resources/css/app.css', 'public/css', [
        require('postcss-import'),
        require('tailwindcss'),
    ])
    .postCss('resources/css/themes/base-theme.css', 'public/css')
    .postCss('resources/css/themes/lavender-theme.css', 'public/css')
    .postCss('resources/css/themes/magic-violet-theme.css', 'public/css')
    .copyDirectory('resources/img', 'public/img');

if (mix.inProduction()) {
    mix.version();
}

mix.disableSuccessNotifications();
