<template>
  <div class="p-6 bg-yellow-50 rounded-md shadow">
    <h2 class="text-xl font-bold mb-3 text-yellow-700">オーナーダッシュボード</h2>
    <p>所有する店舗・施設の管理メニューを表示します。</p>

    <!-- 管理メニュー -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <NuxtLink
        to="/owner/courses"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">講座管理</h3>
        <p class="text-sm text-gray-600">講座情報の登録・編集・削除を行います。</p>
      </NuxtLink>
      <NuxtLink
        to="/owner/import"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">講座CSV一括登録</h3>
        <p class="text-sm text-gray-600">講座情報の一括登録を行います。</p>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
</script>
