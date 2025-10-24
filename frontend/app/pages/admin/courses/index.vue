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

    <!-- ローディング -->
    <div v-if="pending" class="text-gray-600">読み込み中...</div>

    <!-- エラー -->
    <div v-else-if="error" class="text-red-600">
      データの取得に失敗しました。{{ error.message }}
    </div>

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
            <td class="px-4 py-2 border">{{ course.course_title }}</td>
            <td class="px-4 py-2 border">{{ course.instructor }}</td>
            <td class="px-4 py-2 border">
              {{ formatDate(course.date_time) }}
            </td>
            <td class="px-4 py-2 border text-center">{{ course.capacity }}</td>
            <td class="px-4 py-2 border text-center">
              <span
                class="px-2 py-1 rounded text-white text-sm"
                :class="course.status === '公開'
                  ? 'bg-green-500'
                  : 'bg-gray-400'"
              >
                {{ course.status }}
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

<script setup>
import { useFetch } from '#app'

const { data, pending, error, refresh } = await useFetch('/api/admin/courses')
const courses = computed(() => data.value?.data || [])

/**
 * 日付フォーマット
 */
const formatDate = (val) => {
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

/**
 * 削除処理
 */
const deleteCourse = async (id) => {
  if (!confirm('この講座を削除しますか？')) return

  try {
    await $fetch(`/api/admin/courses/${id}`, { method: 'DELETE' })
    alert('削除しました')
    refresh()
  } catch (e) {
    console.error(e)
    alert('削除に失敗しました')
  }
}
</script>
