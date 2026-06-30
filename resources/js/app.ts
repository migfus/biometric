import '../css/app.css';
// import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createApp, DefineComponent, h } from 'vue';
import { ZiggyVue } from '../../vendor/tightenco/ziggy';
import GuestLayout from './Layouts/GuestLayout.vue';

const appName = import.meta.env.VITE_APP_NAME || 'Check In-Out';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: async (name) => {
        const pages = import.meta.glob<DefineComponent>('./pages/**/*.vue', { eager: false })

        const importFn = pages[`./pages/${name}.vue`]

        if (!importFn) {
            throw new Error(`Page not found: ./pages/${name}.vue\nAvailable: ${Object.keys(pages).join('\n')}`)
        }

        const page = (await importFn()).default
        page.layout = page.layout ?? GuestLayout
        return page
    },
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
