<template>
  <header class="header">
    <div class="inner">

      <!-- ロゴ -->
      <NuxtLink to="/" class="logo">
        自治体ボード
      </NuxtLink>

      <!-- PCナビ -->
      <nav class="nav pc-nav">
        <NuxtLink to="/" class="nav-item">トップ</NuxtLink>
        <NuxtLink to="/courses" class="nav-item">講座・教室</NuxtLink>
      </nav>

      <!-- ハンバーガー -->
      <button class="hamburger" @click="open = !open">
        <span :class="{ active: open }"></span>
        <span :class="{ active: open }"></span>
        <span :class="{ active: open }"></span>
      </button>

    </div>

    <!-- モバイルドロワー -->
    <transition name="slide">
      <div v-if="open" class="drawer">
        <NuxtLink to="/" class="drawer-item" @click="close">トップ</NuxtLink>
        <NuxtLink to="/courses" class="drawer-item" @click="close">講座・教室</NuxtLink>
      </div>
    </transition>
  </header>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const open = ref(false)

function close() {
  open.value = false
}
</script>

<style scoped>
/* ================================
   Header base
================================ */
.header {
  position: sticky;
  top: 0;
  z-index: 50;
  background: #ffffff;
  box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.inner {
  max-width: 1200px;
  margin: 0 auto;
  padding: 12px 20px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

/* ================================
   Logo
================================ */
.logo {
  font-size: 1.8rem;
  font-weight: 800;
  color: #2a6dbb;
  text-decoration: none;
}

/* ================================
   PC Navigation
================================ */
.nav {
  display: flex;
  gap: 28px;
}

.nav-item {
  color: #333;
  text-decoration: none;
  font-size: 1rem;
  font-weight: 500;
}

.nav-item:hover {
  color: #2a6dbb;
}

/* ================================
   Hamburger (SP only)
================================ */
.hamburger {
  width: 32px;
  height: 24px;
  display: none;
  flex-direction: column;
  justify-content: space-between;
  background: none;
  border: none;
  padding: 0;
}

.hamburger span {
  display: block;
  width: 100%;
  height: 3px;
  background: #333;
  border-radius: 4px;
  transition: .3s;
}

.hamburger span.active:nth-child(1) {
  transform: translateY(10px) rotate(45deg);
}

.hamburger span.active:nth-child(2) {
  opacity: 0;
}

.hamburger span.active:nth-child(3) {
  transform: translateY(-10px) rotate(-45deg);
}

/* ================================
   Drawer menu (SP)
================================ */
.drawer {
  background: #ffffff;
  box-shadow: 0 4px 10px rgba(0,0,0,.15);
  padding: 20px;
  display: flex;
  flex-direction: column;
}

.drawer-item {
  padding: 12px 0;
  font-size: 1.1rem;
  font-weight: 500;
  color: #333;
  text-decoration: none;
  border-bottom: 1px solid #eee;
}

.drawer-item:last-child {
  border-bottom: none;
}

.slide-enter-active,
.slide-leave-active {
  transition: all .3s ease;
}

.slide-enter-from,
.slide-leave-to {
  transform: translateY(-20px);
  opacity: 0;
}

/* ================================
   Responsive
================================ */
@media (max-width: 768px) {
  .pc-nav {
    display: none;
  }
  .hamburger {
    display: flex;
  }
}
</style>
