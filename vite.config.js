import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    base: '/',
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    // LOCALHOST
    // server: {
    //     watch: {
    //         // Use polling on filesystems that don't support native events (Windows, VM, network shares)
    //         usePolling: true,
    //     },
    // },
    // SERVER
    server: {
        host: '0.0.0.0',
        port: 5173,

        cors: {
            origin: [
                'http://192.168.24.21:8080',
            ],
            credentials: true,
        },

        hmr: {
            host: '192.168.24.21',
            port: 5173,
        },
    }
});
