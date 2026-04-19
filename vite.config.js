import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true
        }),
        tailwindcss()
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**']
        }
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks(id) {
                    if (id.includes('tabulator-tables')) return 'tabulator-tables';
                    if (id.includes('sweetalert2')) return 'sweetalert2';
                }
            }
        }
    }
});
