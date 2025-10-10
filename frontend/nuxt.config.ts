// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  plugins: ["@/plugins/axios"],
  modules: ['@nuxt/eslint', '@nuxtjs/tailwindcss'],
  tailwindcss: {
    cssPath: ["~/assets/css/tailwind.css", { injectPosition: "first" }], // Default
    config: {
      content: [
        "~/components/**/*.{js,vue,ts}",
        "~/layouts/**/*.vue",
        "~/pages/**/*.vue",
        "~/plugins/**/*.{js,ts}",
        "~/app.vue",
        "~/error.vue",
      ],
    },
    viewer: true,
  },
})