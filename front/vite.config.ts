import { defineConfig, loadEnv } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import WindiCSS from 'vite-plugin-windicss'

export default defineConfig(({ mode }) => {
    const env = loadEnv(mode, process.cwd(), '');
    return {
        server: {
            host: env.BASE_URL,
            port: parseInt(env.VITE_PORT),
        },
        plugins: [
            vue(),
            WindiCSS(),
        ],
        resolve: {
            alias: {
                '@': path.resolve(__dirname, './src'),
            },
        }
    }
});
