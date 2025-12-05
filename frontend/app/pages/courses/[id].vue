<template>
  <div>
    <HeaderMain />
    <div class="course-detail-page">
      <h1 class="page-title">{{ course?.course_title }}</h1>

      <div v-if="loading" class="loading">読み込み中...</div>
      <div v-else-if="error" class="error">{{ error }}</div>

      <div v-else class="course-detail">

        <div class="detail-card">
          <p><strong>開催日：</strong>{{ formatDate(course.date_time) }}</p>
          <p><strong>場所：</strong>{{ course.venue }}</p>

          <p v-if="course.participation_fee">
            <strong>参加費：</strong>{{ course.participation_fee }}
          </p>

          <p v-if="course.capacity">
            <strong>定員：</strong>{{ course.capacity }} 名
          </p>

          <p v-if="course.instructor">
            <strong>講師：</strong>{{ course.instructor }}
          </p>

          <p class="content" v-if="course.content" v-html="course.content"></p>

          <!-- ここから参加ボタン -->
          <div class="attend-wrapper">
            <button
              v-if="isLoggedIn && !isAttending"
              @click="attend"
              class="attend-btn"
            >
              講座に参加する
            </button>

            <p v-if="isLoggedIn && isAttending" class="attended-text">
              ✔ 参加済みです
            </p>

            <NuxtLink v-if="!isLoggedIn" to="/login" class="login-link text-blue-500">
              ログインして参加する
            </NuxtLink>
          </div>
        </div>

        <NuxtLink to="/courses" class="back">
          ← 講座一覧へ戻る
        </NuxtLink>
      </div>
    </div>
    <FooterMain />
  </div>
</template>


<script setup lang="ts">
import { ref, onMounted } from "vue"

const route = useRoute()
const { $api } = useNuxtApp()

const course = ref<any>(null)
const loading = ref(true)
const error = ref("")

const isLoggedIn = ref(false)
const isAttending = ref(false)

onMounted(async () => {
  const token = localStorage.getItem("user_token")
  isLoggedIn.value = !!token

  try {
    const id = route.params.id
    const res = await $api.get(`/courses/${id}`)
    course.value = res.data

    if (isLoggedIn.value) {
      // 参加済みチェック
      const check = await $api.get(`/user/attendances/check/${id}`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      isAttending.value = check.data.attending
    }
  } catch (e) {
    error.value = "講座の取得に失敗しました。"
    console.error(e)
  } finally {
    loading.value = false
  }
})

// 参加処理
async function attend() {
  try {
    const token = localStorage.getItem("user_token")
    const courseId = route.params.id

    await $api.post(
      "/user/attendances",
      { course_id: courseId, status: "参加" },
      { headers: { Authorization: `Bearer ${token}` } }
    )

    isAttending.value = true
  } catch (e) {
    console.error("参加に失敗:", e)
    alert("参加処理に失敗しました")
  }
}

function formatDate(dateStr: string) {
  const d = new Date(dateStr)
  return `${d.getFullYear()}年${d.getMonth() + 1}月${d.getDate()}日`
}
</script>


<style scoped>
.course-detail-page {
  padding: 40px 20px;
}

.page-title {
  font-size: 2rem;
  margin-bottom: 20px;
}

.loading,
.error {
  text-align: center;
  padding: 40px;
}

.detail-card {
  background: #fff;
  padding: 24px;
  border-radius: 12px;
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
}

.content {
  margin-top: 20px;
  line-height: 1.7;
}

.back {
  display: inline-block;
  margin-top: 20px;
  color: #2a6dbb;
  font-weight: bold;
}
</style>
