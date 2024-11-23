import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '0.0.0.0', // Set menjadi 0.0.0.0 agar terbuka di semua alamat IP
        port: 5173,       // Gunakan port yang sama
        hmr: {
            host: '192.168.1.7', // Server IP
            port: 5173,
        }
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
