import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import route from "@/ziggy";
import { ZiggyVue } from "ziggy/vue.es";
import Guest from "@/Layouts/Guest.vue";
import * as Sentry from "@sentry/vue";
// import { VuePlausible } from "vue-plausible";

require("./bootstrap");

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText || "Laravel";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => {
        // eslint-disable-next-line @typescript-eslint/no-var-requires
        const page = require(`./Pages/${name}.vue`).default;
        page.layout = page.layout || Guest;
        return page;
    },
    setup({ el, app, props, plugin }) {
        const vueApp = createApp({ render: () => h(app, props) });
        vueApp
            .use(plugin)
            .use(ZiggyVue)
            // .use(VuePlausible)
            .mixin({ methods: { route } })
            .mount(el);

        Sentry.init({
            app: vueApp,
            logErrors: true,
            dsn: process.env.MIX_SENTRY_DSN,
            environment: process.env.MIX_APP_ENV,
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
