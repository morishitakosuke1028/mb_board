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

        <!-- デバッグ情報 -->
        <div v-if="debugInfo" class="mt-8 p-4 bg-gray-100 rounded text-xs">
          <h3 class="font-bold mb-2">デバッグ情報:</h3>
          <pre class="whitespace-pre-wrap">{{ debugInfo }}</pre>
        </div>
      </div>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import type { FetchError } from 'ofetch'

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

// ページ読み込み時にデータ復元
onMounted(() => {
  const saved = sessionStorage.getItem('course_form')
  if (saved) {
    form.value = JSON.parse(saved)

    // Base64 がある場合はプレビュー表示用に設定
    if (form.value.course_image_base64) {
      previewUrl.value = form.value.course_image_base64
    }
  }
})

const isGoogleMapUrl = (url: string) => {
  return typeof url === 'string' && url.startsWith('https://www.google.com/maps/embed')
}

// 戻るボタン
const backToEdit = () => {
  navigateTo('/admin/courses/create')
}

// 登録送信
const submit = async () => {
  if (!form.value || isSubmitting.value) return

  isSubmitting.value = true
  debugInfo.value = ''
  
  const token = localStorage.getItem('admin_token')
  
  // $apiのbaseURLを確認
  console.log('🔍 $api.defaults:', $api.defaults)
  console.log('🔍 $api.defaults.baseURL:', $api.defaults?.baseURL)
  
  const formData = new FormData()

  // FormDataに追加
  for (const key in form.value) {
    if (key === 'course_image_base64') continue
    const value = form.value[key]
    if (value !== null && value !== undefined) {
      formData.append(key, value)
    }
  }

  try {
    const endpoint = '/admin/courses'
    console.log('📍 エンドポイント:', endpoint)
    console.log('📦 FormData 内容:')
    for (const [key, val] of formData.entries()) {
      if (val instanceof File) {
        console.log('   ', key, `[File: ${val.name}, ${val.size} bytes]`)
      } else {
        console.log('   ', key, val)
      }
    }

    // FormDataの場合はネイティブfetchを使用
    // axiosはFormDataを正しく扱えない場合がある
    const baseURL = $api.defaults?.baseURL || 'http://localhost:8000'
    const fullUrl = `${baseURL}${endpoint}`
    
    console.log('📤 送信先URL:', fullUrl)

    const response = await fetch(fullUrl, {
      method: 'POST',
      body: formData,
      headers: { Authorization: `Bearer ${token ?? ''}`, Accept: 'application/json' },
    })

    console.log('📡 ステータス:', response.status)

    const ct = response.headers.get('content-type') || ''
    let payload: any
    if (ct.includes('application/json')) {
      payload = await response.json()
    } else {
      payload = await response.text()
    }

    if (!response.ok) {
      console.error('❌ サーバーエラー:', payload)
      throw new Error(
        typeof payload === 'string' ? payload.slice(0, 200) : (payload?.message || `HTTP ${response.status}`)
      )
    }

    console.log('✅ 登録成功:', payload)

    
    navigateTo('/admin/courses')
  } catch (err: any) {
    console.error('❌ エラー発生')
    console.error('エラーオブジェクト:', err)
    console.error('レスポンス:', err.response)
    console.error('メッセージ:', err.message)

    // デバッグ情報に追加
    debugInfo.value += '\n❌ エラー詳細:\n'
    debugInfo.value += `メッセージ: ${err?.message || '不明'}\n`
    
    if (err?.response?.data) {
      debugInfo.value += `データ: ${JSON.stringify(err.response.data, null, 2)}\n`
    }

    alert(`登録に失敗しました: ${err?.response?.data?.message || err?.message || '不明なエラー'}`)
  } finally {
    isSubmitting.value = false
  }
}
</script>