const mix = require("laravel-mix");
const { exec } = require("child_process");
const chokidar = require("chokidar");

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
mix.extend(
    "ziggy",
    new (class {
        register(config = {}) {
            this.env = config.env ?? "";
            this.watch = config.watch ?? ["routes/**/*.php"];
            this.path = config.path ?? "";
            this.enabled = config.enabled ?? !mix.inProduction();
        }

        boot() {
            if (!this.enabled) return;

            const command = () => {
                console.log(process.env.NODE_ENV);
                console.log(
                    `${this.env} php artisan ziggy:generate ${this.path}`
                );
                exec(
                    `${this.env} php artisan ziggy:generate ${this.path}`,
                    (error, stdout, stderr) => console.log(stdout)
                );
            };

            command();

            if (Mix.isWatching() && this.watch) {
                chokidar.watch(this.watch).on("change", (path) => {
                    console.log(`${path} changed...`);
                    command();
                });
            }
        }
    })()
);

mix.ziggy({
    path: "./resources/js/ziggy.generated.js",
    env: mix.inProduction() ? "APP_URL=https://wordsearch.games" : "",
    enabled: false,
})
    .ts("resources/js/app.ts", "public/js")
    // .eslint({
    //     fix: true,
    //     extensions: ["js", "ts", "vue"],
    // })
    .vue({ version: 3 })
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ])
    .webpackConfig(() => require("./webpack.config"));

if (mix.inProduction()) {
    mix.version().sourceMaps();
}
