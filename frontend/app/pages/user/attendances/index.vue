<template>
  <div class="attendance-page">
    <h1 class="page-title">参加中の講座一覧</h1>

    <div v-if="loading" class="loading">読み込み中...</div>
    <div v-else-if="error" class="error">{{ error }}</div>

    <div v-else class="list-grid">
      <div v-for="item in attendances" :key="item.id" class="attendance-card">

        <h3 class="title">{{ item.course.course_title }}</h3>

        <p class="date">
          開催日：{{ formatDate(item.course.date_time) }}
        </p>

        <p class="venue">場所：{{ item.course.venue }}</p>

        <p class="status">ステータス：{{ item.status }}</p>

        <NuxtLink :to="`/courses/${item.course_id}`" class="detail-link">
          詳しく見る →
        </NuxtLink>

      </div>
    </div>
    <div>
      <NuxtLink :to="`/courses/`">
          講座一覧
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue"

const attendances = ref<any[]>([])
const loading = ref(true)
const error = ref("")

const { $api } = useNuxtApp()

onMounted(async () => {
  try {
    const token = localStorage.getItem("user_token")
    if (!token) {
      return navigateTo("/login")
    }

    const res = await $api.get("/user/attendances", {
      headers: {
        Authorization: `Bearer ${token}`,
      },
    })

    attendances.value = res.data
  } catch (e) {
    console.error(e)
    error.value = "参加中の講座情報を取得できませんでした。"
  } finally {
    loading.value = false
  }
})

function formatDate(dateStr: string) {
  const d = new Date(dateStr)
  return `${d.getFullYear()}年${d.getMonth() + 1}月${d.getDate()}日`
}
</script>

<style scoped>
.attendance-page {
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
.list-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
}
.attendance-card {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}
.title {
  font-weight: bold;
  font-size: 1.2rem;
}
.date,
.venue,
.status {
  margin: 6px 0;
  color: #555;
}
.detail-link {
  color: #2a6dbb;
  font-weight: bold;
  margin-top: 10px;
  display: inline-block;
}
</style>
