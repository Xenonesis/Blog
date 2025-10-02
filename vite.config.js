import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    build: {
        // Configure build to avoid eval() usage
        minify: 'esbuild',
        // Ensure source maps don't use eval
        sourcemap: false,
        rollupOptions: {
            output: {
                // Avoid dynamic imports that might use eval
                manualChunks: undefined,
            },
        },
    },
    esbuild: {
        // Prevent esbuild from using eval
        legalComments: 'none',
    },
});
