<template>
  <div class="flex justify-center items-center h-screen bg-gray-100">
    <div class="bg-white p-8 rounded-xl shadow-md w-96">
      <h1 class="text-2xl font-bold mb-6 text-center">ログイン</h1>

      <!-- ログイン種別タブ -->
      <div class="flex mb-6 border-b">
        <button
          v-for="t in types"
          :key="t"
          class="flex-1 py-2 text-center font-semibold transition"
          :class="activeType === t
            ? 'border-b-4 border-blue-600 text-blue-600'
            : 'text-gray-500 hover:text-gray-700'"
          @click="switchType(t)"
        >
          {{ typeLabels[t] }}
        </button>
      </div>

      <!-- ログインフォーム -->
      <form @submit.prevent="handleLogin">
        <div class="mb-4">
          <label class="block mb-1 font-medium">メールアドレス</label>
          <input
            v-model="email"
            type="email"
            placeholder="example@example.com"
            class="w-full border rounded p-2"
            required
          />
        </div>

        <div class="mb-6">
          <label class="block mb-1 font-medium">パスワード</label>
          <input
            v-model="password"
            type="password"
            placeholder="********"
            class="w-full border rounded p-2"
            required
          />
        </div>

        <button
          type="submit"
          class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition"
        >
          {{ typeLabels[activeType] }}としてログイン
        </button>

        <p v-if="error" class="text-red-500 mt-3 text-center">{{ error }}</p>
      </form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRouter } from "vue-router"
import { useAuth } from "@/composables/useAuth"

const router = useRouter()
const { login } = useAuth()

const email = ref("")
const password = ref("")
const error = ref("")

// ログイン種別
const types = ["user", "owner", "admin"] as const
const typeLabels = { user: "ユーザー", owner: "オーナー", admin: "管理者" }
const activeType = ref<typeof types[number]>("user")

const switchType = (type: typeof types[number]) => {
  activeType.value = type
  error.value = ""
  email.value = ""
  password.value = ""
}

const handleLogin = async () => {
  error.value = ""
  try {
    const success = await login(activeType.value, email.value, password.value)
    if (success) {
      router.push("/dashboard")
    } else {
      error.value = "メールアドレスまたはパスワードが正しくありません。"
    }
  } catch (err: any) {
    if (err.response?.data?.errors?.email) {
      error.value = err.response.data.errors.email[0]
    } else {
      error.value = "ログイン中にエラーが発生しました。"
    }
  }
}
</script>
