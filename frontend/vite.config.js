import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'node:path'
import { fileURLToPath, URL } from 'node:url'

export default defineConfig(({ command }) => ({
  plugins: [vue()],
  base: command === 'build' ? '/app/' : '/',

  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url)),
    },
  },

  build: {
    outDir: path.resolve(__dirname, '../web/app'),
    emptyOutDir: true,
  },

  // css: {
  //   preprocessorOptions: {
  //     scss: {
  //       additionalData:
  //         `@use "@/styles/scss/variables.scss" as *;` +
  //         `@use "@/styles/scss/mixins.scss" as *;`,
  //     },
  //   },
  // },
}))
