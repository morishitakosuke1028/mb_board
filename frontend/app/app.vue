<template>
  <ClientOnly>
    <div id="app" class="min-h-screen bg-gray-50 transition-opacity duration-300 opacity-100">
      <NuxtPage />
    </div>
  </ClientOnly>
</template>

<style>
html, body, #__nuxt, #app {
  min-height: 100%;
  background-color: #f9fafb;
}

.fade-enter-active, .fade-leave-active {
  transition: opacity 0.25s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>
