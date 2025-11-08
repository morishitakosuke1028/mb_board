<template>
  <div class="p-6 bg-blue-50 rounded-md shadow">
    <h2 class="text-xl font-bold mb-4 text-blue-700">管理者ダッシュボード</h2>

    <p class="mb-6 text-gray-700">
      カテゴリ管理・ユーザー管理などのメニューを表示します。
    </p>

    <!-- 管理メニュー -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <!-- カテゴリ管理 -->
      <NuxtLink
        to="/admin/categories"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">カテゴリ管理</h3>
        <p class="text-sm text-gray-600">カテゴリの一覧・追加・編集を行います。</p>
      </NuxtLink>

      <NuxtLink
        to="/admin/owners"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">オーナー管理</h3>
        <p class="text-sm text-gray-600">オーナー情報の確認・削除を行います。</p>
      </NuxtLink>

      <NuxtLink
        to="/admin/users"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">ユーザー管理</h3>
        <p class="text-sm text-gray-600">ユーザー情報の確認・削除を行います。</p>
      </NuxtLink>
      <NuxtLink
        to="/admin/courses"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">講座管理</h3>
        <p class="text-sm text-gray-600">講座情報の登録・編集・削除を行います。</p>
      </NuxtLink>
      <NuxtLink
        to="/admin/import"
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
