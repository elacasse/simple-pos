import { fileURLToPath, URL } from 'node:url'

import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'

// https://vite.dev/config/
export default defineConfig({
  plugins: [
    vue(),
    vueDevTools(),
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  server: {
    host: '0.0.0.0',        // allow access from outside the container
    port: 5173,             // expose this in docker-compose
    strictPort: true,       // avoid falling back to another port
    hmr: {
      host: 'localhost',    // or use your Docker host IP if needed
    }
  }
})
