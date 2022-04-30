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
            'primary-50': '#f8fafb',
            'primary-100': '#e9f0fc',
            'primary-200': '#d1d8f9',
            'primary-300': '#acb3f0',
            'primary-400': '#8d8ae5',
            'primary-500': '#7465dc',
            'primary-600': '#5f49cb',
            'primary-700': '#4837a9',
            'primary-800': '#31257a',
            'primary-900': '#1b174b',

            'secondary-50': '#f9fafa',
            'secondary-100': '#f0f1f8',
            'secondary-200': '#dedbf1',
            'secondary-300': '#bcb6de',
            'secondary-400': '#9b8cc5',
            'secondary-500': '#7e67ac',
            'secondary-600': '#654b8e',
            'secondary-700': '#4c386c',
            'secondary-800': '#332649',
            'secondary-900': '#1d172b',

            'warning-50': '#fdf2f8',
            'warning-100': '#fce7f3',
            'warning-200': '#fbcfe8',
            'warning-300': '#f9a8d4',
            'warning-400': '#f472b6',
            'warning-500': '#ec4899',
            'warning-600': '#db2777',
            'warning-700': '#be185d',
            'warning-800': '#9d174d',
            'warning-900': '#831843',

            'gray-50': '#f9fafb',
            'gray-100': '#f3f4f6',
            'gray-200': '#e5e7eb',
            'gray-300': '#d1d5db',
            'gray-400': '#9ca3af',
            'gray-500': '#6b7280',
            'gray-600': '#4b5563',
            'gray-700': '#374151',
            'gray-800': '#1f2937',
            'gray-900': '#111827',

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
