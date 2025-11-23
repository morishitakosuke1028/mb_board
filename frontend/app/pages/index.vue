<template>
  <div>
    <HeaderMain />
    <!-- ヒーローセクション -->
    <section class="hero">
      <div class="hero__content">
        <h1>生涯学習のまち 宇治市</h1>
        <p>“まNAVI”で探そう、学びの宇治。</p>
      </div>
      <div class="hero__image">
        <img src="/images/hero-top.png" alt="宇治市 学び イメージ">
      </div>
    </section>

    <!-- 検索／カテゴリリンクセクション -->
    <section class="search-links">
      <div class="link-card" v-for="item in searchLinks" :key="item.label">
        <NuxtLink :to="item.href">{{ item.label }}</NuxtLink>
      </div>
    </section>

    <!-- オススメ講座セクション -->
    <section class="recommend">
      <h2>おすすめの講座・教室</h2>
      <div class="cards-grid">
        <CardCourse v-for="course in recommendCourses" :key="course.id" :course="course" />
      </div>
    </section>

    <!-- フッター -->
    <FooterMain />
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import HeaderMain from '~/components/HeaderMain.vue'
import CardCourse from '@/components/CardCourse.vue';
import FooterMain from '@/components/FooterMain.vue';

interface Course {
  id: number;
  title: string;
  tags: string[];
  date_time: string;
  fee: string;
  place: string;
  imageUrl?: string;
}

interface NewsItem {
  id: number;
  title: string;
  published_at: string;
}

const searchLinks = [
  { label: '講座・教室をさがす', href: '/courses' },
  { label: '教室を運営されている方へ', href: '/for-organizers' },
];

const recommendCourses = ref<Course[]>([]);
const newsList = ref<NewsItem[]>([]);

// 仮データフェッチ（API連携時に差し替え）
onMounted(async () => {
  // APIから取得例
  // recommendCourses.value = await fetch('/api/courses?recommend=true').then(r=>r.json());
  recommendCourses.value = [
    { id: 1, title: '和泉史塾「和泉の古代寺院と仏像」', tags: ['歴史・文化・教養'], date_time: '2025-11-19 13:30', fee: '無料', place: 'いずみの国歴史館' },
    { id: 2, title: 'POPすく～る', tags: ['子育て・体験'], date_time: '2025-12-14 14:00', fee: '無料', place: 'TRC北部リージョンセンター図書室' },
    // …その他
  ];
  newsList.value = [
    { id: 101, title: '活動報告 姉妹都市アメリカ ミネソタ州ブルーミントン市に派遣しました！', published_at: '2025-11-06' },
    { id: 102, title: 'イベント情報 第69回和泉市民文化祭が開催されます', published_at: '2025-09-25' },
    // …その他
  ];
});
</script>

<style scoped>
.hero {
  display: flex;
  align-items: center;
  padding-bottom: 80px;
  padding-left: 20px;
  padding-right: 20px;
  background: #f4f9f4;
}
.hero__content {
  flex: 1;
}
.hero__content h1 {
  font-size: 3rem;
  margin-bottom: 1rem;
}
.hero__content p {
  font-size: 1.25rem;
  color: #555;
}
.hero__image img {
  max-width: 100%;
  height: auto;
}
.search-links {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
  gap: 20px;
  padding: 40px 20px;
}
.link-card {
  background: #fff;
  border-radius: 8px;
  padding: 40px 20px;
  text-align: center;
  box-shadow: 0 2px 8px rgba(0,0,0,.1);
}
.recommend {
  padding: 60px 20px;
}
.recommend h2 {
  font-size: 2rem;
  margin-bottom: 20px;
}
.cards-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px,1fr));
  gap: 24px;
}
.news {
  padding: 60px 20px;
  background: #fafafa;
}
.news h2 {
  font-size: 2rem;
  margin-bottom: 20px;
}
.news ul {
  list-style: none;
  padding: 0;
}
.news ul li {
  margin-bottom: 12px;
}
</style>
