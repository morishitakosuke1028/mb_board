<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">カテゴリ編集</h1>

    <div v-if="loading">読み込み中...</div>

    <form v-else @submit.prevent="updateCategory" class="space-y-4 w-1/2">
      <div>
        <label class="block font-semibold mb-1">カテゴリ名</label>
        <input
          v-model="category.name"
          type="text"
          class="border border-black rounded p-2 w-full"
        />
      </div>

      <div class="flex gap-4">
        <button
          type="submit"
          class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700"
        >
          更新
        </button>
        <button
          type="button"
          @click="navigateTo('/admin/categories')"
          class="bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-500"
        >
          戻る
        </button>
      </div>
    </form>

    <p v-if="error" class="text-red-600 mt-4">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
const route = useRoute()
const { $api } = useNuxtApp()
const category = ref<any>({})
const error = ref("")
const loading = ref(true)

onMounted(async () => {
  try {
    const token = localStorage.getItem("admin_token")
    const res = await $api.get(`/admin/categories/${route.params.id}/edit`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    category.value = res.data.data
  } catch (err: any) {
    console.error("取得失敗:", err)
    error.value = "カテゴリ情報の取得に失敗しました"
  } finally {
    loading.value = false
  }
})

const updateCategory = async () => {
  try {
    const token = localStorage.getItem("admin_token")
    await $api.put(
      `/admin/categories/${route.params.id}`,
      { name: category.value.name },
      { headers: { Authorization: `Bearer ${token}` } }
    )
    alert("更新しました")
    navigateTo("/admin/categories")
  } catch (err: any) {
    console.error("更新失敗:", err)
    error.value = err.response?.data?.message || "更新に失敗しました"
  }
}
</script>
