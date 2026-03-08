<template>
    <div>
        <h1 class="text-3xl font-bold mb-8">Dashboard Overview</h1>

        <div class="grid grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow">
                <h2 class="text-lg">Posts</h2>
                <p class="text-3xl font-bold">{{ stats.total_posts }}</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h2 class="text-lg">Comments</h2>
                <p class="text-3xl font-bold">{{ stats.total_comments }}</p>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow">
                <h2 class="text-lg">Users</h2>
                <p class="text-3xl font-bold">{{ stats.total_users }}</p>
            </div>
        </div>

        <!-- Latest Posts -->
        <div class="bg-white p-6 rounded-2xl shadow mb-8">
            <h2 class="text-xl font-semibold mb-4">Latest Posts</h2>

            <div
                v-for="post in stats.latest_posts"
                :key="post.id"
                class="border-b py-2"
            >
                {{ post.title }}
            </div>
        </div>

        <!-- Latest Comments -->
        <div class="bg-white p-6 rounded-2xl shadow">
            <h2 class="text-xl font-semibold mb-4">Latest Comments</h2>

            <div
                v-for="comment in stats.latest_comments"
                :key="comment.id"
                class="border-b py-2"
            >
                {{ comment.content }}
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "../../api/axios";

const stats = ref({
    total_posts: 0,
    total_comments: 0,
    total_users: 0,
    latest_posts: [],
    latest_comments: [],
});

const loadStats = async () => {
    const response = await axios.get("/dashboard/stats");
    stats.value = response.data;
};

onMounted(loadStats);
</script>
