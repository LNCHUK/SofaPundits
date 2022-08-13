import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import postcssNesting from "postcss-nesting";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: [
                'resources/routes/**',
                'routes/**',
                'resources/views/**',
            ],
        }),
        postcssNesting()
    ],
    server: {
        host: '192.168.56.56',
        watch: {
            usePolling: true,
        },
    }
});
