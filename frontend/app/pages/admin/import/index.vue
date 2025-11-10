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
          ref="fileRef"
          id="file"
          type="file"
          accept=".csv"
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

const fileRef = ref<HTMLInputElement | null>(null)
const isSubmitting = ref(false)
const message = ref('')
const error = ref('')

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
  isSubmitting.value = true
  message.value = ''
  error.value = ''

  try {
    const input = fileRef.value
    const file = input?.files?.[0]
    if (!file) {
      isSubmitting.value = false
      return alert('CSVファイルを選択してください')
    }

    const fd = new FormData()
    // ← フィールド名は **csv_file**（Laravel側と一致）
    fd.append('csv_file', file, file.name)

    // デバッグ: 実際に積まれているか確認
    for (const [k, v] of fd.entries()) {
      console.log('FD:', k, v instanceof File ? `${v.name} (${v.type}, ${v.size}B)` : v)
    }

    const token = localStorage.getItem('admin_token') || ''

    // ✅ 重要: Content-Type は **指定しない**
    const res = await $api.post('/admin/import', fd, {
      headers: {
        Authorization: `Bearer ${token}`,
        Accept: 'application/json',
      },
      // 万一 $api のデフォで application/json が付く環境なら念のため外す
      transformRequest: [(data, headers) => {
        if (headers) {
          delete headers['Content-Type']
          delete headers['content-type']
        }
        return data
      }],
      maxBodyLength: Infinity, // 念のため
    })

    message.value = res.data?.message || 'インポート完了しました。'
  } catch (err: any) {
    console.error('❌ インポート失敗:', err)
    error.value =
      err?.response?.data?.message ||
      (err?.response?.data?.errors ? JSON.stringify(err.response.data.errors) : 'インポートに失敗しました')
  } finally {
    isSubmitting.value = false
  }
}


</script>
