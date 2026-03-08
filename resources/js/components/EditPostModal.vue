<template>
    <div
        v-if="visible"
        class="fixed inset-0 bg-black/50 flex items-center justify-center"
    >
        <div class="bg-white w-[500px] p-6 rounded-2xl">
            <h2 class="text-xl font-bold mb-5">Edit Post</h2>

            <input
                v-model="form.title"
                class="border w-full p-2 rounded mb-3"
                placeholder="Title"
            />

            <textarea
                v-model="form.content"
                class="border w-full p-2 rounded mb-4"
                rows="5"
                placeholder="Content"
            />
            <select v-model="form.status">
                <option value="draft">Draft</option>
                <option value="published">Published</option>
            </select>
            <div class="flex justify-end gap-3">
                <button
                    @click="updatePost"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Save
                </button>

                <button
                    @click="$emit('close')"
                    class="bg-gray-400 px-4 py-2 rounded"
                >
                    Cancel
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from "vue";
import axios from "../api/axios";

const props = defineProps({
    visible: Boolean,
    post: Object,
});

const emit = defineEmits(["close", "updated"]);

const form = ref({
    title: "",
    content: "",
    status: "",
});

watch(
    () => props.post,
    (newPost) => {
        if (newPost) {
            form.value.title = newPost.title;
            form.value.content = newPost.content;
            form.value.status = newPost.status;
        }
    },
);

const updatePost = async () => {
    await axios.put(`/posts/${props.post.id}`, form.value);

    emit("updated");
    emit("close");
};
</script>
