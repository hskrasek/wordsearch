import "./bootstrap";
import "../css/app.css";

import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { resolvePageComponent } from "laravel-vite-plugin/inertia-helpers";
import { InertiaProgress } from "@inertiajs/progress";
import { ZiggyVue } from "ziggy-vue";
import route from "ziggy";
import * as Sentry from "@sentry/vue";

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        // page.then((mod) => {
        //     // eslint-disable-next-line @typescript-eslint/ban-ts-comment
        //     // @ts-ignore
        //     mod.default.layout = mod.default.layout || App;
        // });

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
