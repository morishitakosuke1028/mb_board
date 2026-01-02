<template>
  <div class="profile-page">
    <h1 class="title">プロフィール編集</h1>

    <div v-if="loading" class="loading">読み込み中...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <form v-else @submit.prevent="updateProfile" class="form">
      <label>
        名前
        <input v-model="form.name" type="text" required />
      </label>

      <label>
        フリガナ
        <input v-model="form.kana" type="text" required />
      </label>

      <label>
        会社名
        <input v-model="form.company_name" type="text" />
      </label>

      <label>
        会社名（カナ）
        <input v-model="form.company_kana" type="text" />
      </label>

      <label>
        連絡先郵便番号
        <input v-model="form.contact_zip" type="text" inputmode="numeric" />
      </label>

      <label>
        連絡先住所
        <input v-model="form.contact_address" type="text" />
      </label>

      <label>
        連絡先電話番号
        <input v-model="form.contact_tel" type="tel" inputmode="tel" />
      </label>

      <label>
        請求先郵便番号
        <input v-model="form.secret_zip" type="text" inputmode="numeric" required />
      </label>

      <label>
        請求先住所
        <input v-model="form.secret_address" type="text" required />
      </label>

      <label>
        請求先電話番号
        <input v-model="form.secret_tel" type="tel" inputmode="tel" required />
      </label>

      <label>
        メールアドレス
        <input v-model="form.email" type="email" required />
      </label>

      <label>
        パスワード（変更する場合のみ入力）
        <input v-model="form.password" type="password" placeholder="変更する場合のみ" />
      </label>

      <button type="submit" class="btn submit">更新する</button>
    </form>

    <button class="btn delete" @click="deleteAccount">
      アカウントを削除する（退会）
    </button>

    <div class="back">
      <NuxtLink to="/dashboard">← マイページに戻る</NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue"
import { useRoute, useRouter } from "vue-router"
import { useAuth } from "@/composables/useAuth"

const route = useRoute()
const router = useRouter()
const { $api } = useNuxtApp()
const { fetchUser, logout } = useAuth()

const form = ref({
  name: "",
  kana: "",
  company_name: "",
  company_kana: "",
  contact_zip: "",
  contact_address: "",
  contact_tel: "",
  secret_zip: "",
  secret_address: "",
  secret_tel: "",
  email: "",
  password: "",
})

const loading = ref(true)
const error = ref("")

// ★ URLの [id] 取得
const ownerId = route.params.id

// 初期ロード
onMounted(async () => {
  const token = localStorage.getItem("owner_token")
  if (!token) return router.push("/login")

  try {
    const res = await $api.get(`/owner/profile/${ownerId}/edit`, {
      headers: { Authorization: `Bearer ${token}` }
    })

    form.value.name = res.data.name ?? ""
    form.value.kana = res.data.kana ?? ""
    form.value.company_name = res.data.company_name ?? ""
    form.value.company_kana = res.data.company_kana ?? ""
    form.value.contact_zip = res.data.contact_zip ?? ""
    form.value.contact_address = res.data.contact_address ?? ""
    form.value.contact_tel = res.data.contact_tel ?? ""
    form.value.secret_zip = res.data.secret_zip ?? ""
    form.value.secret_address = res.data.secret_address ?? ""
    form.value.secret_tel = res.data.secret_tel ?? ""
    form.value.email = res.data.email ?? ""

  } catch (e) {
    console.error(e)
    error.value = "プロフィールを取得できませんでした。"
  } finally {
    loading.value = false
  }
})

/** プロフィール更新 */
const updateProfile = async () => {
  try {
    const token = localStorage.getItem("owner_token")

    await $api.put(`/owner/profile/${ownerId}`, form.value, {
      headers: { Authorization: `Bearer ${token}` }
    })

    alert("プロフィールを更新しました")
    fetchUser()

  } catch (e) {
    console.error(e)
    alert("更新に失敗しました")
  }
}

/** アカウント削除（SoftDelete） */
const deleteAccount = async () => {
  if (!confirm("本当に退会しますか？この操作は取り消せません。")) return

  try {
    const token = localStorage.getItem("owner_token")

    await $api.delete(`/owner/profile/${ownerId}`, {
      headers: { Authorization: `Bearer ${token}` }
    })

    alert("アカウントを削除しました。")
    logout()

  } catch (e) {
    console.error(e)
    alert("削除に失敗しました")
  }
}
</script>


<style scoped>
.profile-page {
  max-width: 600px;
  margin: 40px auto;
  padding: 20px;
}
.title {
  font-size: 2rem;
  margin-bottom: 20px;
}
.loading,
.error {
  text-align: center;
  margin: 20px 0;
}
.form label {
  display: block;
  margin-bottom: 15px;
}
.form input {
  width: 100%;
  padding: 10px;
  border:1px solid #ddd;
  border-radius: 6px;
  margin-top: 4px;
}
.btn {
  display: inline-block;
  margin-top:15px;
  padding:10px 18px;
  border-radius:6px;
  font-weight:600;
  cursor:pointer;
}
.submit { background:#2a6dbb; color:#fff; }
.delete { background:#ff4d4f; color:#fff; margin-top:25px; }
.back { margin-top:30px; }
</style>
