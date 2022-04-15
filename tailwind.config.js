const defaultTheme = require("tailwindcss/defaultTheme");

module.exports = {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/js/**/*.vue",
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ["Nunito", ...defaultTheme.fontFamily.sans],
            },
            colors: {
                semi: {
                    75: "rgba(0, 0, 0, 0.75)",
                    85: "rgba(0, 0, 0, 0.85)",
                },
            },
        },
    },

    plugins: [require("@tailwindcss/forms")],
};
