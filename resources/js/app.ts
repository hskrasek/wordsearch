import { createApp, h } from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import route from "@/ziggy";
import { ZiggyVue } from "ziggy/vue.es";
import Guest from "@/Layouts/Guest.vue";

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
        createApp({ render: () => h(app, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });
