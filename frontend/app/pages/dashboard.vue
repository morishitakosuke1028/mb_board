<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-4">ダッシュボード</h1>

    <div v-if="user">
      <p>ログイン中: {{ user.name }}（{{ user.email }}）</p>
      <p v-if="loginType">ログイン種別: {{ loginType }}</p>
      <button
        @click="logoutAndRedirect"
        class="mt-4 bg-red-600 text-white py-2 px-4 rounded hover:bg-red-700"
      >
        ログアウト
      </button>

      <hr class="my-6" />

      <component :is="currentDashboard" />
    </div>
  </div>
</template>

<script setup lang="ts">
definePageMeta({
  middleware: ['auth']
})
import { useAuth } from "@/composables/useAuth"
import AdminDashboard from "@/components/dashboard/AdminDashboard.vue"
import UserDashboard from "@/components/dashboard/UserDashboard.vue"
import OwnerDashboard from "@/components/dashboard/OwnerDashboard.vue"

const { user, fetchUser, logout } = useAuth()
const router = useRouter()
const loginType = ref<string | null>(null)

const dashboards: Record<"admin" | "user" | "owner", any> = {
  admin: AdminDashboard,
  user: UserDashboard,
  owner: OwnerDashboard,
}

const currentDashboard = computed(() => {
  const type = (loginType.value as "admin" | "user" | "owner") || "user"
  return dashboards[type]
})

onMounted(() => {
  loginType.value = window.localStorage.getItem("login_type")
  fetchUser()
})

const logoutAndRedirect = async () => {
  await logout()
  router.push("/login")
}
</script>
