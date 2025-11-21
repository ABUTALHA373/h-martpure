// vite.config.js
import {defineConfig} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '192.168.0.109', // Allows access from any device on the network
        port: 8001, // Port for Vite's server
        cors: {
            origin: [
                'http://192.168.0.109:8000',
                'http://192.168.0.109:8001'
            ],
            // credentials: true
        },
    }
});
