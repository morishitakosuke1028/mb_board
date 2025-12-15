<template>
  <div class="p-8 space-y-6">
    <header>
      <h1 class="text-2xl font-bold">受講管理</h1>
      <p class="text-gray-600 text-sm mt-1">
        登録されている受講状況の一覧です。不要なレコードは削除できます。
      </p>
    </header>

    <div v-if="loading" class="text-gray-500">読み込み中...</div>
    <p v-else-if="error" class="text-red-600">{{ error }}</p>

    <table v-else class="w-full border border-gray-200 text-sm">
      <thead>
        <tr class="bg-gray-50 text-left">
          <th class="p-2 border">ID</th>
          <th class="p-2 border">ユーザー</th>
          <th class="p-2 border">コース</th>
          <th class="p-2 border">ステータス</th>
          <th class="p-2 border">登録日</th>
          <th class="p-2 border w-32">操作</th>
        </tr>
      </thead>
      <tbody>
        <tr
          v-for="attendance in attendances"
          :key="attendance.id"
          class="hover:bg-gray-50 transition"
        >
          <td class="p-2 border">{{ attendance.id }}</td>
          <td class="p-2 border">
            <div class="font-semibold">
              {{ findUserName(attendance.user_id) }}
            </div>
            <div class="text-gray-500 text-xs">
              ID: {{ attendance.user_id }}
            </div>
          </td>
          <td class="p-2 border">
            <div class="font-semibold">{{ attendance.course?.title }}</div>
            <div class="text-gray-500 text-xs">ID: {{ attendance.course_id }}</div>
          </td>
          <td class="p-2 border">
            <span class="inline-flex items-center px-2 py-1 rounded bg-blue-50 text-blue-700">
              {{ attendance.status }}
            </span>
          </td>
          <td class="p-2 border">{{ formatDate(attendance.created_at) }}</td>
          <td class="p-2 border text-center">
            <button
              class="text-red-600 hover:text-red-800"
              @click="deleteAttendance(attendance.id)"
            >
              削除
            </button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from "vue";

const { $api } = useNuxtApp();

type Attendance = {
  id: number;
  user_id: number;
  course_id: number;
  status: string;
  created_at: string;
  user?: { id: number; name: string; email: string };
  course?: { id: number; title: string };
};

const attendances = ref<Attendance[]>([]);
const users = ref<Record<number, string>>({});
const loading = ref(true);
const error = ref("");

const fetchAttendances = async () => {
  try {
    const token = localStorage.getItem("admin_token");
    if (!token) {
      return navigateTo("/login");
    }

    const res = await $api.get("/admin/attendances", {
      headers: { Authorization: `Bearer ${token}` },
    });
    attendances.value = res.data.data;

    // ユーザー情報をマッピング
    res.data.users?.forEach((user: { id: number; name: string }) => {
      users.value[user.id] = user.name;
    });
  } catch (err: any) {
    console.error("受講一覧の取得に失敗しました", err);
    error.value = err.response?.data?.message || "受講一覧の取得に失敗しました";
  } finally {
    loading.value = false;
  }
};

const deleteAttendance = async (id: number) => {
  if (!confirm("この受講情報を削除しますか？")) return;

  try {
    const token = localStorage.getItem("admin_token");
    await $api.delete(`/admin/attendances/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });
    attendances.value = attendances.value.filter((item) => item.id !== id);
  } catch (err: any) {
    console.error("受講情報の削除に失敗しました", err);
    error.value = err.response?.data?.message || "受講情報の削除に失敗しました";
  }
};

const findUserName = (userId: number) => {
  return users.value[userId] || "不明なユーザー";
};
const formatDate = (dateStr: string) => {
  if (!dateStr) return "-";
  return new Date(dateStr).toLocaleString("ja-JP", {
    year: "numeric",
    month: "2-digit",
    day: "2-digit",
    hour: "2-digit",
    minute: "2-digit",
  });
};

onMounted(fetchAttendances);
</script>

<style scoped>
table {
  border-collapse: collapse;
}
</style>
