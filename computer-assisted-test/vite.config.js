import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base:null,
                    includeAbsolute: false
                }
            }
        })
    ],
    resolve: {
        alias: {
            '#minpath': path.resolve(__dirname, 'node_modules/vfile/lib/minpath.browser.js'),
            '#minproc': path.resolve(__dirname, 'node_modules/vfile/lib/minproc.browser.js'),
            '#minurl': path.resolve(__dirname, 'node_modules/vfile/lib/minurl.browser.js')
        }
    }
});
