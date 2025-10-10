<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-4">ダッシュボード</h1>

    <div v-if="user">
      <p>ログイン中: {{ user.name }}（{{ user.email }}）</p>
      <p v-if="loginType">ログイン種別: {{ loginType }}</p>
      <button
        @click="logout"
        class="mt-4 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700"
      >
        ログアウト
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from "@/composables/useAuth"
const { user, fetchUser, logout } = useAuth()

const loginType = ref<string | null>(null)

onMounted(() => {
  loginType.value = window.localStorage.getItem("login_type")
  fetchUser()
})
</script>
