<template>
    <div
        v-if="user && user.role === 'admin'"
        class="bg-gray-100 p-3 flex gap-4"
    >
        <router-link to="/admin/posts" class="text-blue-600">Posts</router-link>
        <router-link to="/categories" class="text-blue-600"
            >Categories</router-link
        >
        <router-link to="/admin/comments" class="text-blue-600"
            >Comments</router-link
        >
    </div>
    <div
        v-if="user && user.role === 'editor'"
        class="bg-gray-100 p-3 flex gap-4"
    >
        <router-link to="/admin/posts" class="text-blue-600">Posts</router-link>
    </div>
</template>

<script setup>
import { ref } from "vue";
const user = ref(JSON.parse(localStorage.getItem("user")));
</script>
