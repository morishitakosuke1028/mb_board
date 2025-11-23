<template>
  <div class="course-slider">
    <h2 class="title">新着講座・教室</h2>

    <div ref="slider" class="slider-container">
      <div class="slider-track" :style="{ transform: `translateX(-${current * 100}%)` }">
        <div class="slide" v-for="item in courses" :key="item.id">
          <div class="slide-card">
            <h3>{{ item.course_title }}</h3>

            <p class="date">{{ formatDate(item.date_time) }}</p>
            <p class="venue">{{ item.venue }}</p>

            <NuxtLink :to="`/courses/${item.id}`" class="readmore">
              詳しく見る
            </NuxtLink>
          </div>
        </div>
      </div>
    </div>

    <!-- 必要ならナビゲーション -->
    <div class="nav">
      <button @click="prev">＜</button>
      <button @click="next">＞</button>
    </div>
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
const current = ref(0)

onMounted(async () => {
  const res = await $api.get("/courses/latest")
  courses.value = res.data

  // 自動スライド
  setInterval(next, 3000)
})

function next() {
  current.value = (current.value + 1) % courses.value.length
}

function prev() {
  current.value =
    (current.value - 1 + courses.value.length) % courses.value.length
}

function formatDate(dateStr: string) {
  const d = new Date(dateStr)
  return `${d.getFullYear()}年${d.getMonth() + 1}月${d.getDate()}日`
}
</script>

<style scoped>
.course-slider {
  padding: 60px 20px;
  background: #fafafa;
}

.title {
  font-size: 2rem;
  margin-bottom: 20px;
}

.slider-container {
  overflow: hidden;
  width: 100%;
}

.slider-track {
  display: flex;
  transition: transform 0.5s ease;
}

.slide {
  min-width: 100%;
  padding-right: 20px;
}

.slide-card {
  background: #fff;
  padding: 20px;
  border-radius: 12px;
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.1);
}

.nav {
  display: flex;
  justify-content: center;
  gap: 10px;
  margin-top: 10px;
}

.nav button {
  padding: 8px 12px;
  border: none;
  background: #ddd;
  border-radius: 4px;
  cursor: pointer;
}
</style>
