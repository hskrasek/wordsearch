const mix = require("laravel-mix");
const path = require("path");

require("laravel-mix-eslint");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.alias({
    ziggy: path.resolve("vendor/tightenco/ziggy/dist/vue"),
})
    .js("resources/js/app.js", "public/js")
    .vue()
    .eslint({
        fix: true,
        extensions: ["js", "vue"],
    })
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ])
    .webpackConfig(require("./webpack.config"));

if (mix.inProduction()) {
    mix.version();
}
