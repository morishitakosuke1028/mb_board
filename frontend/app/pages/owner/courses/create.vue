<template>
  <section class="text-gray-600 body-font relative">
    <div class="container px-5 py-24 mx-auto">
      <div class="flex flex-col text-center w-full mb-12">
        <h1 class="sm:text-3xl text-2xl font-medium title-font mb-4 text-gray-900">講座登録</h1>
      </div>
      <form @submit.prevent="submit">
        <div class="lg:w-1/2 md:w-2/3 mx-auto">
          <div class="flex flex-wrap -m-2">
            <div class="p-2 w-1/2">
              <div class="relative">
                <label class="leading-7 text-sm text-gray-600">運営者</label>
                <input
                  type="text"
                  :value="ownerName"
                  class="w-full bg-gray-500 bg-opacity-50 rounded border border-gray-300 text-gray-700 py-1 px-3"
                  disabled
                >
                <input type="hidden" v-model="form.owner_id" />
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="course_title" class="leading-7 text-sm text-gray-600">講座タイトル</label>
                <input type="text" v-model="form.course_title" id="course_title" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-full">
              <div class="relative">
                <label for="content" class="leading-7 text-sm text-gray-600">講座内容</label>
                <textarea id="content" v-model="form.content" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 h-32 text-base outline-none text-gray-700 py-1 px-3 resize-none leading-6 transition-colors duration-200 ease-in-out"></textarea>
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="course_image" class="leading-7 text-sm text-gray-600">講座画像</label>
                <input type="file" @change="handleFileChange" id="course_image" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="instructor" class="leading-7 text-sm text-gray-600">講師名</label>
                <input type="text" v-model="form.instructor" id="instructor" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="instructor_title" class="leading-7 text-sm text-gray-600">講師肩書</label>
                <input type="text" v-model="form.instructor_title" id="instructor_title" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="date_time" class="leading-7 text-sm text-gray-600">開催日時</label>
                <input type="datetime-local" v-model="form.date_time" id="date_time" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="participation_fee" class="leading-7 text-sm text-gray-600">参加費</label>
                <input type="text" v-model="form.participation_fee" id="participation_fee" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="additional_fee" class="leading-7 text-sm text-gray-600">別途費用</label>
                <input type="text" v-model="form.additional_fee" id="additional_fee" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="capacity" class="leading-7 text-sm text-gray-600">定員</label>
                <input type="number" v-model="form.capacity" id="capacity" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="venue" class="leading-7 text-sm text-gray-600">会場</label>
                <input type="text" v-model="form.venue" id="venue" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="venue_zip" class="leading-7 text-sm text-gray-600">会場郵便番号</label>
                <input type="text" v-model="form.venue_zip" placeholder="123-456" id="venue_zip" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="venue_address" class="leading-7 text-sm text-gray-600">会場住所</label>
                <input type="text" v-model="form.venue_address" id="venue_address" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="tel" class="leading-7 text-sm text-gray-600">連絡先電話番号</label>
                <input type="text" v-model="form.tel" id="tel" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="email" class="leading-7 text-sm text-gray-600">連絡先メールアドレス</label>
                <input type="email" v-model="form.email" id="email" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="map" class="leading-7 text-sm text-gray-600">マップURL</label>
                <input type="text" v-model="form.map" id="map" class="w-full bg-gray-100 bg-opacity-50 rounded border border-gray-300 focus:border-indigo-500 focus:bg-white focus:ring-2 focus:ring-indigo-200 text-base outline-none text-gray-700 py-1 px-3 leading-8 transition-colors duration-200 ease-in-out">
              </div>
            </div>
            <div class="p-2 w-1/2">
              <div class="relative">
                <label for="map" class="leading-7 text-sm text-gray-600">ステータス</label>
                <select
                  v-model="form.status"
                  class="w-full border rounded p-2"
                  required
                >
                  <option value="1">公開</option>
                  <option value="0">非公開</option>
                </select>
              </div>
            </div>
            <div class="p-2 w-full">
              <button type="submit" class="flex mx-auto text-white bg-indigo-500 border-0 py-2 px-8 focus:outline-none hover:bg-indigo-600 rounded text-lg">確認画面へ</button>
            </div>
          </div>
        </div>
      </form>
    </div>
  </section>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { useRoute } from "vue-router";
import { useAuth } from "@/composables/useAuth";

const route = useRoute();
const { user, fetchUser } = useAuth();

const loading = ref(true);
const error = ref("");
const ownerName = ref("");

const form = ref<{
  owner_id: string
  course_title: string
  content: string
  course_image: File | null
  course_image_base64?: string
  course_image_name?: string
  instructor: string
  instructor_title: string
  date_time: string
  participation_fee: string
  additional_fee: string
  capacity: string
  venue: string
  venue_zip: string
  venue_address: string
  tel: string
  email: string
  map: string
  status: string
}>({
  owner_id: '',
  course_title: '',
  content: '',
  course_image: null,
  instructor: '',
  instructor_title: '',
  date_time: '',
  participation_fee: '',
  additional_fee: '',
  capacity: '',
  venue: '',
  venue_zip: '',
  venue_address: '',
  tel: '',
  email: '',
  map: '',
  status: '1',
});

onMounted(async () => {
  if (route.query.reset !== "true") {
    const saved = sessionStorage.getItem('course_form');
    if (saved) {
      const savedForm = JSON.parse(saved);
      Object.assign(form.value, savedForm);
    }
  } else {
    sessionStorage.removeItem('course_form');
  }

  try {
    const token = localStorage.getItem("owner_token");
    if (!token) {
      console.warn("No owner token found");
      return navigateTo("/login");
    }

    let ownerProfile = user.value;
    if (!ownerProfile) {
      ownerProfile = await fetchUser();
    }

    if (!ownerProfile) {
      return navigateTo("/login");
    }

    form.value.owner_id = ownerProfile.id?.toString() ?? "";
    ownerName.value = ownerProfile.name ?? "";
  } catch (err: any) {
    console.error("オーナー取得エラー:", err);
    error.value = "オーナー情報の取得に失敗しました";
  } finally {
    loading.value = false;
  }
});

const handleFileChange = (event: Event) => {
  const input = event.target as HTMLInputElement | null;
  if (!input || !input.files || input.files.length === 0) {
    form.value.course_image = null;
    form.value.course_image_base64 = undefined;
    form.value.course_image_name = undefined;
    return;
  }

  const file = input.files[0];
  if (file) {
    form.value.course_image = file;
    form.value.course_image_name = file.name;

    const reader = new FileReader();
    reader.onload = () => {
      form.value.course_image_base64 = reader.result as string;
    };
    reader.readAsDataURL(file);
  }
};

const submit = async () => {
  const formCopy: any = { ...form.value };
  delete formCopy.course_image;
  sessionStorage.setItem("course_form", JSON.stringify(formCopy));
  await navigateTo("/owner/courses/confirm");
};
</script>
