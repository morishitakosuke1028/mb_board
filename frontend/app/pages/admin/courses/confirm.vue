<template>
  <section class="text-gray-600 body-font relative">
    <div class="container px-5 py-24 mx-auto">
      <div class="flex flex-col text-center w-full mb-12">
        <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">
          講座登録内容の確認
        </h1>
      </div>

      <div v-if="!form" class="text-center text-red-600">
        データが見つかりません。
        <NuxtLink to="/admin/courses/create" class="text-blue-600 underline ml-2">
          入力画面に戻る
        </NuxtLink>
      </div>

      <div v-else class="lg:w-1/2 md:w-2/3 mx-auto bg-white p-8 rounded shadow">
        <table class="table-auto w-full border border-gray-300 mb-8">
          <tbody>
            <tr v-for="(label, key) in labels" :key="key">
              <th class="w-1/3 px-4 py-2 text-left bg-gray-50 border">{{ label }}</th>
              <td class="px-4 py-2 border">
                <template v-if="key === 'course_image'">
                  <div v-if="previewUrl">
                    <img :src="previewUrl" alt="講座画像" class="w-40 rounded" />
                  </div>
                  <div v-else>画像なし</div>
                </template>
                <template v-else-if="key === 'map'">
                  <div v-if="isGoogleMapUrl(form[key])">
                    <iframe
                      :src="form[key]"
                      width="100%"
                      height="250"
                      style="border:0;"
                      loading="lazy"
                      referrerpolicy="no-referrer-when-downgrade"
                    ></iframe>
                  </div>
                  <div v-else>
                    {{ form[key] || '地図なし' }}
                  </div>
                </template>
                <template v-else-if="key === 'status'">
                  <div v-if="form[key] == 1">
                    公開
                  </div>
                  <div v-else>
                    非公開
                  </div>
                </template>

                <!-- 通常テキスト -->
                <template v-else>
                  {{ form[key] }}
                </template>
              </td>
            </tr>
          </tbody>
        </table>

        <div class="flex justify-between">
          <button
            @click="backToEdit"
            class="bg-gray-400 text-white px-6 py-2 rounded hover:bg-gray-500 transition"
          >
            戻る
          </button>

          <button
            @click="submit"
            :disabled="isSubmitting"
            class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700 transition disabled:bg-gray-400"
          >
            {{ isSubmitting ? '送信中...' : 'この内容で登録する' }}
          </button>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

const { $api } = useNuxtApp()

const form = ref<any>(null)
const previewUrl = ref<string | null>(null)
const isSubmitting = ref(false)
const debugInfo = ref('')

const labels: Record<string, string> = {
  owner_id: '運営者ID',
  course_title: '講座タイトル',
  content: '講座内容',
  course_image: '講座画像',
  instructor: '講師名',
  instructor_title: '講師肩書',
  date_time: '開催日時',
  participation_fee: '参加費',
  additional_fee: '別途費用',
  capacity: '定員',
  venue: '会場',
  venue_zip: '会場郵便番号',
  venue_address: '会場住所',
  tel: '電話番号',
  email: 'メールアドレス',
  map: 'マップURL',
  status: 'ステータス',
}

const isGoogleMapUrl = (url: string) => {
  return typeof url === 'string' && url.startsWith('https://www.google.com/maps/embed')
}

onMounted(() => {
  const saved = sessionStorage.getItem('course_form')
  if (saved) {
    const parsed = JSON.parse(saved)
    form.value = parsed

    if (form.value.course_image_base64) {
      previewUrl.value = form.value.course_image_base64

      try {
        const arr = form.value.course_image_base64.split(',')
        const mime = arr[0].match(/:(.*?);/)[1]
        const bstr = atob(arr[1])
        let n = bstr.length
        const u8arr = new Uint8Array(n)
        while (n--) u8arr[n] = bstr.charCodeAt(n)
        const file = new File(
          [u8arr],
          form.value.course_image_name || 'upload.png',
          { type: mime }
        )
        form.value.course_image = file
      } catch (e) {
        console.warn('Base64 → File変換失敗', e)
      }
    }
  }
})

const backToEdit = () => {
  navigateTo('/admin/courses/create')
}

const submit = async () => {
  if (!form.value || isSubmitting.value) return

  isSubmitting.value = true
  debugInfo.value = ''
  
  const token = localStorage.getItem('admin_token')
  const formData = new FormData()

  for (const key in form.value) {
    if (key === 'course_image_base64' || key === 'course_image_name') continue
    const value = form.value[key]
    if (value !== null && value !== undefined) {
      formData.append(key, value)
    }
  }

  try {
    const endpoint = '/admin/courses'
    const baseURL = $api.defaults?.baseURL || 'http://localhost:8000'
    const fullUrl = `${baseURL}${endpoint}`

    for (const [key, val] of formData.entries()) {
      if (val instanceof File) {
        console.log(`  ${key}: [File: ${val.name}, ${val.size} bytes]`)
      } else {
        console.log(`  ${key}: ${val}`)
      }
    }

    const response = await fetch(fullUrl, {
      method: 'POST',
      body: formData,
      headers: {
        Authorization: `Bearer ${token ?? ''}`,
        Accept: 'application/json',
      },
    })

    const ct = response.headers.get('content-type') || ''
    let payload: any
    if (ct.includes('application/json')) {
      payload = await response.json()
    } else {
      payload = await response.text()
    }
    sessionStorage.removeItem('course_form')
    navigateTo('/admin/courses')
  } catch (err: any) {
    alert(`登録に失敗しました}`)
  } finally {
    isSubmitting.value = false
  }
}
</script>
