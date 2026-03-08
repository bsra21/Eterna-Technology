<template>
    <div
        v-if="visible"
        class="fixed inset-0 bg-black/50 flex items-center justify-center"
    >
        <div class="bg-white w-[600px] p-8 rounded-2xl shadow-xl">
            <h2 class="text-2xl font-bold mb-6">Create Post</h2>

            <input
                v-model="form.title"
                placeholder="Title"
                class="w-full border p-3 rounded-xl mb-4"
            />

            <textarea
                v-model="form.content"
                placeholder="Content"
                class="w-full border p-3 rounded-xl mb-4"
            />
            <select
                v-model="form.categories"
                multiple
                class="w-full border p-3 rounded-xl mb-4"
            >
                <option
                    v-for="category in categories"
                    :key="category.id"
                    :value="category.id"
                >
                    {{ category.name }}
                </option>
            </select>
            <select v-model="form.status" class="border p-2 rounded w-full">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            <input type="file" @change="handleFile" />

            <div class="flex justify-end gap-3 mt-6">
                <button
                    @click="$emit('close')"
                    class="px-4 py-2 border rounded-xl"
                >
                    Cancel
                </button>

                <button
                    @click="submit"
                    :disabled="loading"
                    class="bg-blue-600 text-white px-5 py-2 rounded-xl"
                >
                    {{ loading ? "Saving..." : "Save Post" }}
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "../api/axios";
import { onMounted } from "vue";

onMounted(async () => {
    const res = await axios.get("/categories");
    categories.value = res.data;
});
defineProps({
    visible: Boolean,
});

const emit = defineEmits(["close", "created"]);
const loading = ref(false);
const form = ref({
    title: "",
    content: "",
    cover: null,
    status: "draft",
    categories: [],
});
const categories = ref([]);
const handleFile = (e) => {
    form.value.cover = e.target.files[0];
};

const submit = async () => {
    if (loading.value) return;

    loading.value = true;

    if (!form.value.title || !form.value.content) {
        alert("Title ve Content boş olamaz!");
        loading.value = false;
        return;
    }

    const data = new FormData();

    data.append("title", form.value.title);
    data.append("content", form.value.content);
    data.append("status", form.value.status);

    form.value.categories.forEach((id) => {
        data.append("categories[]", id);
    });

    if (form.value.cover) {
        data.append("cover", form.value.cover);
    }

    try {
        await axios.post("/posts", data);

        emit("created");
        emit("close");

        form.value.title = "";
        form.value.content = "";
        form.value.cover = null;
        form.value.categories = [];
    } catch (error) {
        console.log(error.response.data);
        alert(JSON.stringify(error.response.data.errors));
    } finally {
        loading.value = false;
    }
};
</script>
