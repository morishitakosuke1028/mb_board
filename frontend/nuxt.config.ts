// https://nuxt.com/docs/api/configuration/nuxt-config
export default defineNuxtConfig({
  compatibilityDate: '2025-07-15',
  devtools: { enabled: true },
  modules: ['@nuxt/eslint', '@nuxtjs/tailwindcss'],
  tailwindcss: {
    cssPath: ["~/app/assets/css/tailwind.css", { injectPosition: "first" }], // Default
    config: {
      content: [
        "~/app/components/**/*.{js,vue,ts}",
        "~/app/layouts/**/*.vue",
        "~/app/pages/**/*.vue",
        "~/app/plugins/**/*.{js,ts}",
        "~/app/app.vue",
        "~/app/error.vue",
      ],
    },
    viewer: true,
  },
})