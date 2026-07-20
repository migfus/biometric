import '../css/app.css'
// import './bootstrap';

import { createInertiaApp } from '@inertiajs/vue3'
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers'
import { createApp, DefineComponent, h } from 'vue'
import { ZiggyVue } from '../../vendor/tightenco/ziggy'
import Layout from './layouts/Layout.vue'
import { createPinia } from 'pinia'

const appName = import.meta.env.VITE_APP_NAME || 'Check In-Out'
const pages = import.meta.glob<{ default: DefineComponent }>('./pages/**/*.vue')
const pinia = createPinia()

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent<{ default: DefineComponent }>(
            `./pages/${name}.vue`,
            pages,
        ).then((pageModule) => {
            const page = pageModule.default
            page.layout = page.layout ?? Layout
            return page
        }),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(pinia)
            .use(ZiggyVue)
            .mount(el)
    },
    progress: {
        color: '#4B5563',
    },
})
