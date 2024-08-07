const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.tsx',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito Sans', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                gray: colors.zinc,
            },
        },
    },

    plugins: [
        require('@tailwindcss/container-queries'),
        require('@tailwindcss/typography'),
    ],
};
