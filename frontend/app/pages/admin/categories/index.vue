<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">カテゴリ一覧</h1>

    <div v-if="loading" class="text-gray-500">読み込み中...</div>

    <table v-else class="w-full border border-gray-300">
      <thead>
        <tr class="bg-gray-100">
          <th class="border p-2 text-left">ID</th>
          <th class="border p-2 text-left">カテゴリ名</th>
          <th class="border p-2 text-left">作成日</th>
          <th class="border p-2 text-left">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="cat in categories" :key="cat.id" class="hover:bg-gray-50">
          <td class="border p-2">{{ cat.id }}</td>
          <td class="border p-2">{{ cat.name }}</td>
          <td class="border p-2">{{ formatDate(cat.created_at) }}</td>
          <td class="border p-2">
            <button
              @click="goToEditPage(cat.id)"
              class="text-blue-600 hover:text-blue-800 font-semibold"
            >
              編集
            </button>
            <button
              @click="deleteCategory(cat.id)"
              class="text-red-600 hover:text-red-800 font-semibold"
            >
              削除
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <div class="mt-6 flex items-center gap-3">
      <input
        v-model="newCategory"
        type="text"
        placeholder="新しいカテゴリ名"
        class="border p-2 rounded w-1/3"
      />
      <button
        @click="addCategory"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
      >
        追加
      </button>
    </div>

    <p v-if="error" class="text-red-600 mt-4">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from "@/composables/useAuth"

const { user } = useAuth()
const { $api } = useNuxtApp()

const categories = ref<any[]>([])
const newCategory = ref("")
const loading = ref(true)
const error = ref("")

onMounted(async () => {
  try {
    const token = localStorage.getItem("admin_token")
    if (!token) {
      console.warn("No admin token found")
      return navigateTo("/login")
    }

    const res = await $api.get("/admin/categories", {
      headers: { Authorization: `Bearer ${token}` },
    })
    categories.value = res.data.data
  } catch (err: any) {
    console.error("カテゴリ取得エラー:", err)
    error.value = "カテゴリの取得に失敗しました"
  } finally {
    loading.value = false
  }
})

// カテゴリ追加
const addCategory = async () => {
  if (!newCategory.value.trim()) return
  try {
    const token = localStorage.getItem("admin_token")
    const res = await $api.post(
      "/admin/categories",
      { name: newCategory.value },
      { headers: { Authorization: `Bearer ${token}` } }
    )
    categories.value.unshift(res.data.data)
    newCategory.value = ""
  } catch (err: any) {
    console.error("追加失敗:", err)
    error.value = err.response?.data?.message || "追加に失敗しました"
  }
}

const goToEditPage = (id: number) => {
  navigateTo(`/admin/categories/${id}/edit`)
}

const deleteCategory = async (id: number) => {
  if (!confirm("本当に削除しますか？")) return
  try {
    const token = localStorage.getItem("admin_token")
    await $api.delete(`/admin/categories/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    categories.value = categories.value.filter((c) => c.id !== id)
  } catch (err: any) {
    console.error("削除失敗:", err)
    error.value = err.response?.data?.message || "削除に失敗しました"
  }
}

const formatDate = (dateStr: string) =>
  new Date(dateStr).toLocaleDateString("ja-JP", {
    year: "numeric",
    month: "short",
    day: "numeric",
  })
</script>
