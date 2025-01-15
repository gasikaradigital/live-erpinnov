import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
        './resources/assets/**/*.{js,vue}',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Inter', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                primary: {
                    DEFAULT: '#666CFF',
                    '50': '#FFFFFF',
                    '100': '#F5F5FF',
                    '200': '#DBDCFF',
                    '300': '#C1C3FF',
                    '400': '#A7AAFF',
                    '500': '#8D91FF',
                    '600': '#666CFF',
                    '700': '#2D35FF',
                    '800': '#0009F3',
                    '900': '#0007BB'
                },
            },
        },
    },

    plugins: [forms, typography],
    prefix: 'tw-',
    important: true,
    darkMode: 'class',
};
