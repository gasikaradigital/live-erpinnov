import defaultTheme from 'tailwindcss/defaultTheme';

export default {
    darkMode: 'class',
    content: [
        './vendor/laravel/jetstream/**/*.blade.php',
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/profile/**/*.blade.php',
        './resources/views/layouts/tailwind.blade.php',
        './resources/views/components/*.blade.php',
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
        require('@tailwindcss/forms'),
        require('@tailwindcss/typography'),
    ],
};
