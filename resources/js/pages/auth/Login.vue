<template>
    <div class="flex items-center justify-center min-h-screen bg-gray-100">
        <div class="w-96 bg-white p-8 rounded-2xl shadow-lg">
            <h2 class="text-2xl font-bold mb-6 text-center">Login</h2>

            <input
                v-model="email"
                placeholder="Email"
                class="w-full p-3 border rounded mb-4"
            />

            <input
                v-model="password"
                type="password"
                placeholder="Password"
                class="w-full p-3 border rounded mb-6"
            />

            <button
                @click="login"
                class="w-full bg-blue-600 text-white p-3 rounded-xl"
            >
                Login
            </button>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios, { setAuthToken } from "../../api/axios";
import { useRouter } from "vue-router";

const email = ref("");
const password = ref("");
const router = useRouter();

const login = async () => {
    const res = await axios.post("/login", {
        login: email.value,
        password: password.value,
    });
    localStorage.setItem("token", res.data.token);
    localStorage.setItem("role", res.data.user.role);
    localStorage.setItem("user", JSON.stringify(res.data.user));

    setAuthToken(res.data.token);

    router.push("/posts");
};
</script>
