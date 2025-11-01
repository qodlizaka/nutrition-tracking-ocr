import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    server: {
        hmr: {
            host: 'localhost',
        },
        watch: {
            usePolling: true,
            ignored: [
                "**/vendor/**",
                "**/bootstrap/**",
                "**/storage/**",
                "**/database/**",
                "**/node_modules/**",
                "**/.github/**",
                "**/tests/**",
            ],
        },
        cors: true,
        port: 5175,
    },
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            publicDirectory: 'public_html',
        }),
        tailwindcss(),
    ],
    build: {
        outDir: 'public_html/build',
    },
});
