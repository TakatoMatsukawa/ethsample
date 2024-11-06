import './bootstrap';

import { createApp, h } from 'vue';
import { createI18n } from "vue-i18n";
import { createInertiaApp } from '@inertiajs/vue3';
import { createPinia } from "pinia";
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';

// Template directives
import clickRipple from "@/directives/clickRipple";

// Bootstrap framework
import * as bootstrap from "bootstrap";

import messages from "./lang/messages.js";
const i18n = createI18n({
    locale: __locale,
    messages,
});

window.bootstrap = bootstrap;

const appName = import.meta.env.VITE_APP_NAME || '佐川町立図書館データベース';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({ el, App, props, plugin }) {
        return createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(createPinia())
            .directive("click-ripple", clickRipple)
            .use(i18n)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
