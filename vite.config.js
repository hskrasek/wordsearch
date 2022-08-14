import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel([]),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            ziggy: "/vendor/tightenco/ziggy/src/js",
            "ziggy-vue": "/vendor/tightenco/ziggy/src/js/vue",
        },
    },
});
