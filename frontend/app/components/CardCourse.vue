<template>
  <div class="card">
    <div class="card-image">
      <img :src="course.imageUrl || '/images/no-image.jpg'" :alt="course.title" />
    </div>

    <div class="card-body">
      <!-- タグ（複数対応） -->
      <div class="tags">
        <span v-for="tag in course.tags" :key="tag" class="tag">
          {{ tag }}
        </span>
      </div>

      <!-- タイトル -->
      <h3 class="title">{{ course.title }}</h3>

      <!-- 開催日時 -->
      <p class="date_time">
        <Icon name="mdi-calendar" />
        {{ formatDate(course.date_time) }}
      </p>

      <!-- 開催場所 -->
      <p class="place">
        <Icon name="mdi-map-marker" />
        {{ course.place }}
      </p>

      <!-- 詳細ボタン -->
      <NuxtLink :to="`/courses/${course.id}`" class="detail-button">
        詳しく見る
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps({
  course: {
    type: Object,
    required: true,
  },
});

// 日付をフォーマット
function formatDate(dateStr: string) {
  const d = new Date(dateStr);
  return `${d.getFullYear()}年${d.getMonth() + 1}月${d.getDate()}日 `;
}
</script>

<style scoped>
.card {
  background: #fff;
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 3px 12px rgba(0,0,0,0.08);
  transition: transform .3s ease, box-shadow .3s ease;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}

.card-image img {
  width: 100%;
  height: 180px;
  object-fit: cover;
}

.card-body {
  padding: 16px;
}

.tags {
  margin-bottom: 8px;
}

.tag {
  background: #eef6ff;
  color: #2a6dbb;
  padding: 3px 8px;
  font-size: 12px;
  border-radius: 4px;
  margin-right: 6px;
}

.title {
  font-size: 1.1rem;
  font-weight: 600;
  margin: 10px 0;
  min-height: 2.5em;
}

.date_time,
.place {
  display: flex;
  align-items: center;
  font-size: 0.9rem;
  margin: 4px 0;
  color: #555;
}

.detail-button {
  margin-top: 12px;
  display: inline-block;
  background: #2a6dbb;
  color: #fff;
  padding: 8px 16px;
  border-radius: 6px;
  text-align: center;
  transition: background .2s;
}

.detail-button:hover {
  background: #1d4f8c;
}
</style>
