import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import react from '@vitejs/plugin-react';


export default defineConfig({
    plugins: [
        react({
            // Disable the React fast refresh preamble to avoid runtime preamble detection
            // issues in this dev environment. This prevents the "can't detect preamble"
            // runtime error while still allowing the plugin to handle JSX transforms.
            fastRefresh: false,
        }),
        laravel({
            input: ['resources/js/main.jsx'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173,
        },
        watch: {
            usePolling: true,
        },
    },
});
