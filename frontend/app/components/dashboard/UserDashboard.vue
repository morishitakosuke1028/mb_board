<template>
  <div class="p-6 bg-blue-50 rounded-md shadow">
    <h2 class="text-xl font-bold mb-4 text-blue-700">ユーザーダッシュボード</h2>

    <p class="mb-6 text-gray-700">
      参加予定の講座・教室などのメニューを表示します。
    </p>

    <!-- 管理メニュー -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
      <NuxtLink
        :to="`/user/profile/${user?.id}`"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">ユーザー情報管理</h3>
        <p class="text-sm text-gray-600">ユーザー情報の更新・退会を行います。</p>
      </NuxtLink>
      <NuxtLink
        to="/user/attendances"
        class="block bg-white border border-blue-300 rounded-lg p-4 hover:bg-blue-100 transition"
      >
        <h3 class="text-lg font-semibold text-blue-600 mb-1">参加予定管理</h3>
        <p class="text-sm text-gray-600">参加予定講座の一覧・キャンセルを行います。</p>
      </NuxtLink>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useAuth } from "@/composables/useAuth"

const { user, fetchUser } = useAuth()

// F5更新対策 → ユーザー情報の復元
if (!user.value) {
  fetchUser()
}
</script>
