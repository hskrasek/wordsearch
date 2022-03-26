const defaultTheme = require('tailwindcss/defaultTheme');

module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Nunito', ...defaultTheme.fontFamily.sans],
            },
            gridTemplateRows: {
                // Simple 8 row grid
                '8': 'repeat(8, minmax(0, 1fr))',
                '13': 'repeat(13, minmax(0, 1fr))',
                '34': 'repeat(34, minmax(0, 1fr))',
            },
            gridTemplateColumns: {
                '8': 'repeat(8, minmax(0, 1fr))',
                '13': 'repeat(13, minmax(0, 1fr))',
                '34': 'repeat(34, minmax(0, 1fr))',
            }
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
