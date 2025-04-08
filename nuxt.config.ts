// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },
  modules: [
    '@nuxt/icon',
    '@nuxt/image',
    '@nuxt/scripts',
    '@nuxt/ui',
  ],
  css: ['~/assets/css/main.css'],
  ssr: true,
  ui: { 
    colorMode: true,
  },
  runtimeConfig: {
    public: {
      urlAPI: process.env.API_URL,
     },
  },
})