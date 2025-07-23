import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,
        cors: {
            origin: ['http://localhost:8181', 'http://127.0.0.1:8181'], // ou a origem do Laravel
            credentials: true,
        },
        hmr: {
            host: '127.0.0.1', // ou o IP da máquina host
        },
    },
    plugins: [
        laravel({
            input: 'resources/js/app.js',
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
});
