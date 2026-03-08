<template>
    <div class="flex min-h-screen bg-gray-100">
        <!-- Sidebar -->
        <div class="w-64 bg-white shadow-xl p-6">
            <h2 class="text-xl font-bold mb-8">Blog Admin</h2>

            <nav class="space-y-4">
                <router-link to="/dashboard" class="block"
                    >Dashboard</router-link
                >
                <router-link
                    v-if="role === 'admin'"
                    to="/categories"
                    class="block"
                    >Category</router-link
                >
                <router-link
                    v-if="role === 'admin' || role === 'editor'"
                    to="/posts"
                    class="block"
                    >Posts</router-link
                >
                <router-link
                    v-if="role === 'admin'"
                    to="/admin/comments"
                    class="block"
                    >Comments</router-link
                >
            </nav>
        </div>

        <!-- Content -->
        <div class="flex-1">
            <div class="bg-white shadow p-4 flex justify-end">
                <button @click="logout" class="text-red-600 font-semibold">
                    Logout
                </button>
            </div>

            <div class="p-8">
                <router-view />
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from "../api/axios";
import { useRouter } from "vue-router";
import { ref } from "vue";
const role = ref(localStorage.getItem("role"));
const router = useRouter();

const logout = async () => {
    try {
        await axios.post("/logout");

        localStorage.removeItem("token");
        localStorage.removeItem("role");
        localStorage.removeItem("user");
        router.push("/");
    } catch (error) {
        console.error(error);
    }
};
</script>
