import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0',
        port: 5173,      // For HMR
        hmr: {
            host: '192.168.1.105',
            protocol: 'http',
            port: 5173
        },
    },
    // preview: {
        // port: 8080,      // For asset serving
        // host: '0.0.0.0'
    // },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            // input: ['resources/css/app.css', 'resources/js/app.js', 'resources/js'],
            output: ['public/css/app.css', 'public/js/'],
            refresh: true,
        }),
    ],
});
