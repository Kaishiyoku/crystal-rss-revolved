const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

module.exports = {

    content: [
        './resources/js/**/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        colors: {
            indigo: {
                '50':  '#f8fafb',
                '100': '#e9f0fc',
                '200': '#d1d8f9',
                '300': '#acb3f0',
                '400': '#8d8ae5',
                '500': '#7465dc',
                '600': '#5f49cb',
                '700': '#4837a9',
                '800': '#31257a',
                '900': '#1b174b',
            },
            purple: {
                '50':  '#f9fafa',
                '100': '#f0f1f8',
                '200': '#dedbf1',
                '300': '#bcb6de',
                '400': '#9b8cc5',
                '500': '#7e67ac',
                '600': '#654b8e',
                '700': '#4c386c',
                '800': '#332649',
                '900': '#1d172b',
            },
            gray: colors.gray,
            transparent: 'transparent',
            current: 'currentColor',
            white: '#ffffff',
            black: '#000000',
        },

        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            }
            ,
            typography: (theme) => ({
                dark: {
                    css: {
                        color: theme('colors.gray.400'),
                        h1: {
                            color: theme('colors.gray.400'),
                        },
                        h2: {
                            color: theme('colors.gray.400'),
                        },
                        h3: {
                            color: theme('colors.gray.400'),
                        },
                        h4: {
                            color: theme('colors.gray.400'),
                        },
                        h5: {
                            color: theme('colors.gray.400'),
                        },
                        h6: {
                            color: theme('colors.gray.400'),
                        },
                        a: {
                            color: theme('colors.gray.400'),
                            '&:hover': {
                                color: theme('colors.gray.400'),
                            },
                        },
                    },
                },
            }),
        }
        ,
    },

    plugins: [
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
}
;
