import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { fileURLToPath, URL } from 'node:url';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
    ],
    resolve: {
        alias: {
            '@': fileURLToPath(new URL('./resources/js', import.meta.url)),
            '@asset': fileURLToPath(new URL('./resources/js/asset', import.meta.url)),
            // ⬆ IMPORTANTE: en Laravel, el "src" realmente es /resources/js
        }
    },
    optimizeDeps: {
        include: ['ag-grid-vue3', 'ag-grid-community']
    }
});
