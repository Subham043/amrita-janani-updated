import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/main/app.css', 'resources/css/main/dark.css', 'resources/css/auth/auth.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
