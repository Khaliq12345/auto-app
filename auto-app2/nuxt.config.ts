// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2024-11-01',
  devtools: { enabled: true },

  modules: [
    '@nuxt/icon',
    '@nuxt/image',
    '@nuxt/scripts',
    '@nuxt/ui',
    // '@nuxtjs/supabase',
    // '@nuxtjs/i18n'
  ],

  
  // plugins: ['~/plugins/supabase.client.js'],
  css: ['~/assets/css/main.css'],
  ssr: true,
  ui: { 
    colorMode: true,
    // icons: ['heroicons']
  },
  // icons: ['heroicons', 'lucide'],
  runtimeConfig: {
    public: {
      supabaseUrl: process.env.SUPABASE_URL,
      supabaseKey: process.env.SUPABASE_KEY
     }
  },
  
  app: {
    pageTransition: { name: 'page', mode: 'out-in' }
  },
  // i18n: {
  //   strategy: 'prefix_except_default',
  //   defaultLocale: 'fr',
  //   locales: [
  //     { code: 'fr', name: 'Français', file: '~/locales/fr.json' },
  //     { code: 'en', name: 'English', file: '~/locales/en.json' },
  //   ],
  //   lazy: true,
  //   // langDir: '~/locales',
  //   detectBrowserLanguage: {
  //     useCookie: true,
  //     cookieKey: 'i18n_redirected'
  //   }
  // }
})