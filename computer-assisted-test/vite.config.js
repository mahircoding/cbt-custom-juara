import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import path from 'path';
import fs from 'fs';

const outDir = fs.existsSync('../public_html') ? '../public_html/build' : '../build';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            publicDirectory: '../',
            buildDirectory: 'build',
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
    build: {
        outDir: outDir,
        emptyOutDir: true,
    },
    resolve: {
        alias: {
            '#minpath': path.resolve(__dirname, 'node_modules/vfile/lib/minpath.browser.js'),
            '#minproc': path.resolve(__dirname, 'node_modules/vfile/lib/minproc.browser.js'),
            '#minurl': path.resolve(__dirname, 'node_modules/vfile/lib/minurl.browser.js')
        }
    }
});
