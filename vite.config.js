import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import react from '@vitejs/plugin-react'; // Importato il plugin React
import path from 'path';

export default defineConfig({
    plugins: [
        react(), // Attivato il plugin React
        laravel({
            // Cambiata l'estensione di app.js in app.jsx
            input: ['resources/scss/app.scss', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    resolve: {
        alias: {
            '~icons': path.resolve(__dirname, 'node_modules/bootstrap-icons/font'),
            '~bootstrap': path.resolve(__dirname, 'node_modules/bootstrap'),
            '~resources': '/resources/'
        },
        // Aggiunto il supporto per estensioni .jsx
        extensions: ['.js', '.jsx', '.json'],
    }
});