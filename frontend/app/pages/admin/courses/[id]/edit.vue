<template>
  <section class="text-gray-600 body-font relative">
    <div class="container px-5 py-24 mx-auto">
      <div class="flex flex-col text-center w-full mb-12">
        <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
          講座編集
        </h1>
      </div>

      <div v-if="loading" class="text-center text-gray-600">読み込み中...</div>
      <div v-else-if="error" class="text-center text-red-600">{{ error }}</div>

      <form v-else @submit.prevent="updateCourse">
        <div class="lg:w-1/2 md:w-2/3 mx-auto">
          <div class="flex flex-wrap -m-2">
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="owner" class="leading-7 text-sm text-gray-600">運営者</label>
                <select
                  v-model="form.owner_id"
                  class="w-full border rounded p-2"
                  required
                >
                  <option value="">選択してください</option>
                  <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                    {{ owner.name }}
                  </option>
                </select>
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="course_title" class="leading-7 text-sm text-gray-600">講座タイトル</label>
                <input type="text" v-model="form.course_title" id="course_title" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-full">
              <div class="relative">
                <label for="content" class="leading-7 text-sm text-gray-600">講座内容</label>
                <textarea id="content" v-model="form.content" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="course_image" class="leading-7 text-sm text-gray-600">講座画像</label>
                <input type="file" @change="handleFileChange" id="course_image" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
              <div v-if="form.course_image_base64" class="mt-3">
                <img
                  :src="form.course_image_base64"
                  alt="講座画像プレビュー"
                  class="w-40 rounded shadow border"
                />
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="instructor" class="leading-7 text-sm text-gray-600">講師名</label>
                <input type="text" v-model="form.instructor" id="instructor" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="instructor_title" class="leading-7 text-sm text-gray-600">講師肩書</label>
                <input type="text" v-model="form.instructor_title" id="instructor_title" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="date_time" class="leading-7 text-sm text-gray-600">開催日時</label>
                <input type="datetime-local" v-model="form.date_time" id="date_time" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="participation_fee" class="leading-7 text-sm text-gray-600">参加費</label>
                <input type="text" v-model="form.participation_fee" id="participation_fee" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="additional_fee" class="leading-7 text-sm text-gray-600">別途費用</label>
                <input type="text" v-model="form.additional_fee" id="additional_fee" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="capacity" class="leading-7 text-sm text-gray-600">定員</label>
                <input type="number" v-model="form.capacity" id="capacity" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="venue" class="leading-7 text-sm text-gray-600">会場</label>
                <input type="text" v-model="form.venue" id="venue" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="venue_zip" class="leading-7 text-sm text-gray-600">会場郵便番号</label>
                <input type="text" v-model="form.venue_zip" placeholder="123-456" id="venue_zip" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="venue_address" class="leading-7 text-sm text-gray-600">会場住所</label>
                <input type="text" v-model="form.venue_address" id="venue_address" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="tel" class="leading-7 text-sm text-gray-600">連絡先電話番号</label>
                <input type="text" v-model="form.tel" id="tel" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="email" class="leading-7 text-sm text-gray-600">連絡先メールアドレス</label>
                <input type="email" v-model="form.email" id="email" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="map" class="leading-7 text-sm text-gray-600">マップURL</label>
                <input type="text" v-model="form.map" id="map" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="map" class="leading-7 text-sm text-gray-600">ステータス</label>
                <select
                  v-model="form.status"
                  class="w-full border rounded p-2"
                  required
                >
                  <option value="1">公開</option>
                  <option value="0">非公開</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <div class="p-2 w-full">
          <button
            type="submit"
            :disabled="isSubmitting"
            class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg"
          >
            {{ isSubmitting ? '更新中...' : '更新する' }}
          </button>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'
import { useAuth } from '@/composables/useAuth'

const route = useRoute()
const { $api } = useNuxtApp()
const { user } = useAuth()

const courseId = route.params.id as string
const form = ref<any>({})
const owners = ref<any[]>([])
const error = ref('')
const loading = ref(true)
const isSubmitting = ref(false)

/**
 * 初期データ取得
 */
onMounted(async () => {
  try {
    const token = localStorage.getItem('admin_token')
    if (!token) return navigateTo('/login')

    // 運営者一覧取得
    const ownerRes = await $api.get('/admin/owners', {
      headers: { Authorization: `Bearer ${token}` },
    })
    owners.value = ownerRes.data.data

    // 講座情報取得
    const res = await $api.get(`/admin/courses/${courseId}/edit`, {
      headers: { Authorization: `Bearer ${token}` },
    })
    form.value = res.data.data || {}

    // 画像URLを保持（プレビュー表示用）
    if (form.value.course_image) {
      form.value.course_image_base64 = form.value.course_image
      form.value.course_image_name = form.value.course_image.split('/').pop()
    }
  } catch (err: any) {
    console.error('データ取得失敗:', err)
    error.value = '講座情報の取得に失敗しました。'
  } finally {
    loading.value = false
  }
})

/**
 * ファイル選択変更処理
 */
const handleFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement | null
  const file = input?.files?.[0] ?? null

  form.value.course_image = file
  form.value.course_image_name = file ? file.name : undefined

  if (file) {
    const reader = new FileReader()
    reader.onload = () => {
      form.value.course_image_base64 = reader.result as string
    }
    reader.readAsDataURL(file)
  } else {
    form.value.course_image_base64 = undefined
  }
}

/**
 * 更新処理
 */
const updateCourse = async () => {
  if (isSubmitting.value) return
  isSubmitting.value = true
  error.value = ''

  try {
    const token = localStorage.getItem('admin_token')
    if (!token) return navigateTo('/login')

    const formData = new FormData()
    for (const key in form.value) {
      const value = form.value[key]
      if (
        key === 'course_image_base64' ||
        key === 'course_image_name' ||
        value === undefined ||
        value === null
      )
        continue

      formData.append(key, value)
    }

    // LaravelでPUTを扱うための_hidden _method
    formData.append('_method', 'PUT')

    // APIリクエスト
    const res = await $api.post(`/admin/courses/${courseId}`, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data',
      },
    })

    console.log('✅ 更新成功:', res.data)
    alert('講座情報を更新しました。')
    await navigateTo('/admin/courses')
  } catch (err: any) {
    console.error('❌ 更新失敗:', err)
    error.value =
      err.response?.data?.message ||
      err.response?.data?.errors ||
      '更新に失敗しました。'
  } finally {
    isSubmitting.value = false
  }
}
</script>