<template>
  <div class="p-8">
    <h1 class="text-2xl font-bold mb-6">オーナー一覧</h1>

    <div v-if="loading" class="text-gray-500">読み込み中...</div>

    <table v-else class="w-full border border-gray-300">
      <thead>
        <tr class="bg-gray-100">
          <th class="border p-2 text-left">ID</th>
          <th class="border p-2 text-left">名前</th>
          <th class="border p-2 text-left">メールアドレス</th>
          <th class="border p-2 text-left">登録日</th>
          <th class="border p-2 text-left">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="user in users"
          :key="user.id"
          class="hover:bg-gray-50 transition"
        >
          <td class="border p-2">{{ user.id }}</td>
          <td class="border p-2">{{ user.name }}</td>
          <td class="border p-2">{{ user.email }}</td>
          <td class="border p-2">{{ formatDate(user.created_at) }}</td>
          <td class="border p-2">
            <button
              @click="deleteuser(user.id)"
              class="ml-4 text-red-600 hover:text-red-800 font-semibold"
            >
              削除
            </button>
          </td>
        </tr>
      </tbody>
    </table>

    <p v-if="error" class="text-red-600 mt-4">{{ error }}</p>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useAuth } from "@/composables/useAuth";

const { $api } = useNuxtApp();
const { user } = useAuth();

const users = ref<any[]>([]);
const loading = ref(true);
const error = ref("");

// ユーザー一覧を取得
onMounted(async () => {
  try {
    const token = localStorage.getItem("admin_token");
    if (!token) {
      console.warn("No admin token found");
      return navigateTo("/login");
    }

    const res = await $api.get("/admin/users", {
      headers: { Authorization: `Bearer ${token}` },
    });
    users.value = res.data.data;
  } catch (err: any) {
    console.error("ユーザー取得エラー:", err);
    error.value = "ユーザーの取得に失敗しました";
  } finally {
    loading.value = false;
  }
});

// 削除処理
const deleteuser = async (id: number) => {
  if (!confirm("本当に削除しますか？")) return;

  try {
    const token = localStorage.getItem("admin_token");
    await $api.delete(`/admin/users/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });

    // 削除後に一覧を更新
    users.value = users.value.filter((o) => o.id !== id);
  } catch (err: any) {
    console.error("削除失敗:", err);
    error.value = err.response?.data?.message || "削除に失敗しました";
  }
};

// 日付フォーマット
const formatDate = (dateStr: string) =>
  new Date(dateStr).toLocaleDateString("ja-JP", {
    year: "numeric",
    month: "short",
    day: "numeric",
  });
</script>

<style scoped>
table {
  border-collapse: collapse;
}
</style>
