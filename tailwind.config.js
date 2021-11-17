const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    mode: 'jit',
    purge: [
        './resources/js/**/*.js',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    darkMode: 'media',

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
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
        },
    },

    variants: {
        extend: {
            opacity: ['disabled'],
            typography: ['responsive', 'dark'],
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
