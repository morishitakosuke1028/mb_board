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
        郵便番号
        <input v-model="form.zip" type="text" inputmode="numeric" required />
      </label>

      <label>
        住所
        <input v-model="form.address" type="text" required />
      </label>

      <label>
        電話番号
        <input v-model="form.tel" type="tel" inputmode="tel" required />
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
      <NuxtLink to="/user/attendance">← マイページに戻る</NuxtLink>
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
const { fetchUser, logout, user } = useAuth()

const form = ref({
  name: "",
  kana: "",
  zip: "",
  address: "",
  tel: "",
  email: "",
  password: "",
})

const loading = ref(true)
const error = ref("")

// ★ URLの [id] 取得
const userId = route.params.id

// 初期ロード
onMounted(async () => {
  const token = localStorage.getItem("user_token")
  if (!token) return router.push("/login")

  try {
    // ★ API修正
    const res = await $api.get(`/user/profile/${userId}/edit`, {
      headers: { Authorization: `Bearer ${token}` }
    })

    form.value.name = res.data.name ?? ""
    form.value.kana = res.data.kana ?? ""
    form.value.zip = res.data.zip ?? ""
    form.value.address = res.data.address ?? ""
    form.value.tel = res.data.tel ?? ""
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
    const token = localStorage.getItem("user_token")

    // PUTは変更なし
    await $api.put(`/user/profile/${userId}`, form.value, {
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
    const token = localStorage.getItem("user_token")

    await $api.delete(`/user/profile/${userId}`, {
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
