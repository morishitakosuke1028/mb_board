<template>
  <div class="p-6">
    <h1 class="text-2xl font-bold mb-6">講座一覧</h1>

    <!-- 追加ボタン -->
    <div class="flex justify-end mb-4">
      <NuxtLink
        :to="{ path: '/admin/courses/create', query: { reset: 'true' } }"
        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition"
      >
        新規登録
      </NuxtLink>
    </div>

    <div v-if="loading" class="text-gray-500">読み込み中...</div>

    <!-- テーブル -->
    <div v-else class="overflow-x-auto">
      <table class="min-w-full border border-gray-300">
        <thead class="bg-gray-100 text-gray-700">
          <tr>
            <th class="px-4 py-2 border">ID</th>
            <th class="px-4 py-2 border">講座名</th>
            <th class="px-4 py-2 border">講師</th>
            <th class="px-4 py-2 border">開催日時</th>
            <th class="px-4 py-2 border">定員</th>
            <th class="px-4 py-2 border">ステータス</th>
            <th class="px-4 py-2 border">操作</th>
          </tr>
        </thead>
        <tbody>
          <tr
            v-for="course in courses"
            :key="course.id"
            class="hover:bg-gray-50 transition"
          >
            <td class="px-4 py-2 border text-center">{{ course.id }}</td>
            <td class="px-4 py-2 border text-center">{{ course.course_title }}</td>
            <td class="px-4 py-2 border text-center">{{ course.instructor }}</td>
            <td class="px-4 py-2 border text-center">
              {{ formatDate(course.date_time) }}
            </td>
            <td class="px-4 py-2 border text-center">{{ course.capacity }}</td>
            <td class="px-4 py-2 border text-center">
              <span
                v-if="course.status == 1"
                class="px-2 py-1 rounded text-white text-sm bg-green-500"
              >
                公開
              </span>
              <span
                v-else
                class="px-2 py-1 rounded text-white text-sm bg-gray-400"
              >
                非公開
              </span>
            </td>
            <td class="px-4 py-2 border text-center space-x-2">
              <NuxtLink
                :to="`/admin/courses/${course.id}/edit`"
                class="text-blue-600 hover:underline"
              >
                編集
              </NuxtLink>
              <button
                @click="deleteCourse(course.id)"
                class="text-red-600 hover:underline"
              >
                削除
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from "@/composables/useAuth"

const { user } = useAuth()
const { $api } = useNuxtApp()

const courses = ref<any[]>([])
const loading = ref(true)
const error = ref("")

onMounted(async () => {
  try {
    const token = localStorage.getItem("admin_token")
    if (!token) {
      console.warn("No admin token found")
      return navigateTo("/login")
    }

    const res = await $api.get("/admin/courses", {
      headers: { Authorization: `Bearer ${token}` },
    })
    courses.value = res.data.data
  } catch (err: any) {
    console.error("講座情報の取得エラー:", err)
    error.value = "講座情報の取得に失敗しました"
  } finally {
    loading.value = false
  }
})

const deleteCourse = async (id: number) => {
  if (!confirm("本当に削除しますか？")) return
  try {
    const token = localStorage.getItem("admin_token")
    await $api.delete(`/admin/courses/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    courses.value = courses.value.filter((c) => c.id !== id)
  } catch (err: any) {
    console.error("削除失敗:", err)
    error.value = err.response?.data?.message || "削除に失敗しました"
  }
}

const formatDate = (val: string) => {
  if (!val) return '-'
  const d = new Date(val)
  return d.toLocaleString('ja-JP', {
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
  })
}
</script>
