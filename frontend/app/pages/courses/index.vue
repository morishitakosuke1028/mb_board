<template>
  <div>
    <HeaderMain />

    <div class="course-list-page">
      <h1 class="page-title">講座・教室一覧</h1>
  
      <div v-if="loading" class="loading">読み込み中...</div>
  
      <div v-else class="list-grid">
        <div v-for="course in courses" :key="course.id" class="course-card">
          <h3 class="title">{{ course.course_title }}</h3>
  
          <p class="date">
            開催日：{{ formatDate(course.date_time) }}
          </p>
  
          <p class="venue">場所：{{ course.venue }}</p>
  
          <NuxtLink :to="`/courses/${course.id}`" class="more">
            詳しく見る →
          </NuxtLink>
        </div>
      </div>
    </div>
    <FooterMain />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue"

const { $api } = useNuxtApp()

interface Course {
  id: number
  course_title: string
  date_time: string
  venue: string
}

const courses = ref<Course[]>([])
const loading = ref(true)

onMounted(async () => {
  try {
    const res = await $api.get("/courses")
    courses.value = res.data.data ?? res.data // ページネーション対応
  } catch (e) {
    console.error("講座取得エラー:", e)
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
.course-list-page {
  padding: 40px 20px;
}

.page-title {
  font-size: 2rem;
  margin-bottom: 20px;
}

.loading {
  text-align: center;
  padding: 40px;
}

.list-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
  gap: 20px;
}

.course-card {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 3px 12px rgba(0,0,0,0.1);
}

.title {
  font-size: 1.2rem;
  font-weight: bold;
}

.date,
.venue {
  margin-top: 8px;
  color: #555;
}

.more {
  display: inline-block;
  margin-top: 10px;
  font-weight: bold;
  color: #2a6dbb;
}
</style>
