import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { InertiaProgress } from "@inertiajs/progress";
import { ZiggyVue } from "ziggy-vue";
import route from "ziggy";
import * as Sentry from "@sentry/vue";
import { createPinia } from "pinia";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

const pinia = createPinia();

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        return resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob("./Pages/**/*.vue")
        );
    },
    setup({ el, app, props, plugin }) {
        const vueApp = createApp({ render: () => h(app, props) });
        vueApp
            .use(plugin)
            .use(ZiggyVue)
            .use(pinia)
            .mixin({ methods: { route } })
            .mount(el);

        Sentry.init({
            app: vueApp,
            logErrors: true,
            dsn: import.meta.env.VITE_SENTRY_DSN,
            environment: import.meta.env.VITE_APP_ENV,
            tracesSampleRate: 1.0,
        });

        Sentry.attachErrorHandler(vueApp, {
            attachProps: true,
            hooks: ["activate", "mount", "update"],
            timeout: 2000,
            trackComponents: true,
            logErrors: true,
        });

        vueApp.mixin(
            Sentry.createTracingMixins({
                hooks: ["activate", "mount", "update"],
                timeout: 2000,
                trackComponents: true,
            })
        );
    },
});

InertiaProgress.init({ color: "#4B5563" });
