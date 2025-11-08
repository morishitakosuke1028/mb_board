<template>
  <section class="text-gray-700 body-font">
    <div class="container px-5 py-24 mx-auto text-center">
      <h1 class="text-2xl font-bold mb-6">講座CSVインポート</h1>

      <!-- サンプルCSVダウンロード -->
      <div class="mb-6">
        <button
          @click="downloadSample"
          class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-6 rounded"
        >
          サンプルCSVをダウンロード
        </button>
      </div>

      <!-- CSVアップロードフォーム -->
      <form @submit.prevent="submitImport" class="space-y-4">
        <input
          type="file"
          accept=".csv"
          @change="handleFileChange"
          class="border p-2 rounded w-full max-w-md mx-auto"
        />

        <div class="mt-4">
          <button
            type="submit"
            :disabled="isSubmitting"
            class="bg-indigo-500 hover:bg-indigo-600 text-white font-bold py-2 px-6 rounded"
          >
            {{ isSubmitting ? 'インポート中...' : 'インポート実行' }}
          </button>
        </div>
      </form>

      <p v-if="message" class="mt-4 text-green-600 font-semibold">{{ message }}</p>
      <p v-if="error" class="mt-4 text-red-600 font-semibold">{{ error }}</p>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref } from 'vue'
const { $api } = useNuxtApp()

const csvFile = ref<File | null>(null)
const isSubmitting = ref(false)
const message = ref('')
const error = ref('')

/**
 * ファイル選択
 */
const handleFileChange = (e: Event) => {
  const input = e.target as HTMLInputElement
  csvFile.value = input.files?.[0] ?? null
}

/**
 * サンプルCSVダウンロード
 */
const downloadSample = async () => {
  try {
    const token = localStorage.getItem('admin_token')
    const res = await fetch(`${$api.defaults.baseURL}/admin/import/sample`, {
      method: 'GET',
      headers: { Authorization: `Bearer ${token}` },
    })
    if (!res.ok) throw new Error('ダウンロードに失敗しました')

    const blob = await res.blob()
    const url = window.URL.createObjectURL(blob)
    const a = document.createElement('a')
    a.href = url
    a.download = 'course_sample.csv'
    a.click()
    window.URL.revokeObjectURL(url)
  } catch (err) {
    console.error(err)
    alert('サンプルCSVのダウンロードに失敗しました')
  }
}

/**
 * CSVインポート送信
 */
const submitImport = async () => {
  if (!csvFile.value) {
    alert('CSVファイルを選択してください')
    return
  }

  isSubmitting.value = true
  message.value = ''
  error.value = ''

  const formData = new FormData()
  formData.append('csv_file', csvFile.value)

  try {
    const token = localStorage.getItem('admin_token')
    const res = await fetch(`${$api.defaults.baseURL}/admin/courses/import`, {
      method: 'POST',
      headers: { Authorization: `Bearer ${token}` },
      body: formData,
    })

    const data = await res.json()
    if (!res.ok) throw new Error(data.message || 'インポートに失敗しました')

    message.value = data.message || 'インポート完了しました。'
  } catch (err: any) {
    error.value = err.message
  } finally {
    isSubmitting.value = false
  }
}
</script>
