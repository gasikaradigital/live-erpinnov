import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';
import typography from '@tailwindcss/typography';
import plugin from 'tailwindcss/plugin';

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
                display: ['Clash Display', ...defaultTheme.fontFamily.sans],
                code: ['Fira Code', ...defaultTheme.fontFamily.mono],
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
                success: {
                    DEFAULT: '#4CAF50',
                    '50': '#E8F5E9',
                    '100': '#C8E6C9',
                    '200': '#A5D6A7',
                    '300': '#81C784',
                    '400': '#66BB6A',
                    '500': '#4CAF50',
                    '600': '#43A047',
                    '700': '#388E3C',
                    '800': '#2E7D32',
                    '900': '#1B5E20'
                },
            },
        },
    },

    plugins: [
        forms,
        typography,
        plugin(({ addUtilities, addComponents, theme }) => {
            addUtilities({
                '.glass-effect': {
                    'background': 'rgba(255, 255, 255, 0.25)',
                    'backdrop-filter': 'blur(10px)',
                    '-webkit-backdrop-filter': 'blur(10px)',
                    'border': '1px solid rgba(255, 255, 255, 0.18)',
                },
                '.text-shadow': {
                    'text-shadow': '2px 2px 4px rgba(0, 0, 0, 0.2)',
                },
                '.text-shadow-sm': {
                    'text-shadow': '1px 1px 2px rgba(0, 0, 0, 0.1)',
                },
            });

            addComponents({
                '.btn': {
                    padding: `${theme('spacing.2')} ${theme('spacing.4')}`,
                    borderRadius: theme('borderRadius.md'),
                    fontWeight: theme('fontWeight.medium'),
                },
                '.btn-primary': {
                    backgroundColor: theme('colors.primary.600'),
                    color: theme('colors.white'),
                    '&:hover': {
                        backgroundColor: theme('colors.primary.700'),
                    },
                },
                '.btn-success': {
                    backgroundColor: theme('colors.success.600'),
                    color: theme('colors.white'),
                    '&:hover': {
                        backgroundColor: theme('colors.success.700'),
                    },
                },
            });
        }),
    ],

    //prefix: 'tw-',
    important: true,
    darkMode: 'class',
};
