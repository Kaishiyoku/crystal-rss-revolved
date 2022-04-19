const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

const withOpacityValue = (variable) => {
    return ({opacityValue}) => {
        return opacityValue === undefined ? `rgb(var(${variable}))` : `rgba(var(${variable}, opacityValue))`;
    }
};

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
            'primary-50': withOpacityValue('--color-primary-50'),
            'primary-100': withOpacityValue('--color-primary-100'),
            'primary-200': withOpacityValue('--color-primary-200'),
            'primary-300': withOpacityValue('--color-primary-300'),
            'primary-400': withOpacityValue('--color-primary-400'),
            'primary-500': withOpacityValue('--color-primary-500'),
            'primary-600': withOpacityValue('--color-primary-600'),
            'primary-700': withOpacityValue('--color-primary-700'),
            'primary-800': withOpacityValue('--color-primary-800'),
            'primary-900': withOpacityValue('--color-primary-900'),

            'secondary-50': withOpacityValue('--color-secondary-50'),
            'secondary-100': withOpacityValue('--color-secondary-100'),
            'secondary-200': withOpacityValue('--color-secondary-200'),
            'secondary-300': withOpacityValue('--color-secondary-300'),
            'secondary-400': withOpacityValue('--color-secondary-400'),
            'secondary-500': withOpacityValue('--color-secondary-500'),
            'secondary-600': withOpacityValue('--color-secondary-600'),
            'secondary-700': withOpacityValue('--color-secondary-700'),
            'secondary-800': withOpacityValue('--color-secondary-800'),
            'secondary-900': withOpacityValue('--color-secondary-900'),

            'gray-50': withOpacityValue('--color-gray-50'),
            'gray-100': withOpacityValue('--color-gray-100'),
            'gray-200': withOpacityValue('--color-gray-200'),
            'gray-300': withOpacityValue('--color-gray-300'),
            'gray-400': withOpacityValue('--color-gray-400'),
            'gray-500': withOpacityValue('--color-gray-500'),
            'gray-600': withOpacityValue('--color-gray-600'),
            'gray-700': withOpacityValue('--color-gray-700'),
            'gray-800': withOpacityValue('--color-gray-800'),
            'gray-900': withOpacityValue('--color-gray-900'),

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
