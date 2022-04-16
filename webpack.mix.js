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
            this.watch = config.watch ?? ["routes/**/*.php"];
            this.path = config.path ?? "";
            this.enabled = config.enabled ?? !mix.inProduction();
        }

        boot() {
            if (!this.enabled) return;

            const command = () =>
                exec(
                    `php artisan ziggy:generate ${this.path}`,
                    (error, stdout, stderr) => console.log(stdout)
                );

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

mix.ts("resources/js/app.ts", "public/js")
    .eslint({
        fix: true,
        extensions: ["js", "ts", "vue"],
    })
    .vue({ version: 3 })
    .sourceMaps()
    .postCss("resources/css/app.css", "public/css", [
        require("postcss-import"),
        require("tailwindcss"),
        require("autoprefixer"),
    ])
    .webpackConfig(() => require("./webpack.config"))
    .ziggy({
        path: "./resources/js/ziggy.generated.js",
        enabled: true,
    });

if (mix.inProduction()) {
    mix.version();
}
