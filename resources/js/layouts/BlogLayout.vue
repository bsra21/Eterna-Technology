<template>
    <div>
        <!-- HEADER -->
        <!-- Admin Menu -->
        <AdminMenu />
        <div class="flex justify-between items-center p-4 border-b bg-white">
            <h1 class="text-xl font-bold">My Blog</h1>

            <div class="flex gap-3">
                <button
                    v-if="!user"
                    @click="showLogin = true"
                    class="text-blue-600"
                >
                    Login
                </button>

                <button
                    v-if="!user"
                    @click="showRegister = true"
                    class="bg-blue-600 text-white px-3 py-1 rounded"
                >
                    Register
                </button>

                <div v-if="user" class="flex items-center gap-3">
                    <span>{{ user.first_name }}</span>

                    <button @click="logout" class="text-red-600">Logout</button>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT -->
        <div class="p-6">
            <router-view />
        </div>

        <!-- REGISTER MODAL -->
        <div
            v-if="showRegister"
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-xl w-96">
                <h2 class="text-xl mb-4">Register</h2>

                <input
                    v-model="registerForm.first_name"
                    placeholder="First name"
                    class="border w-full p-2 mb-2"
                />

                <input
                    v-model="registerForm.last_name"
                    placeholder="Last name"
                    class="border w-full p-2 mb-2"
                />

                <input
                    v-model="registerForm.phone"
                    placeholder="Phone"
                    class="border w-full p-2 mb-2"
                />

                <input
                    v-model="registerForm.email"
                    placeholder="Email"
                    class="border w-full p-2 mb-2"
                />

                <input
                    type="password"
                    v-model="registerForm.password"
                    placeholder="Password"
                    class="border w-full p-2 mb-4"
                />

                <div class="flex justify-end gap-3">
                    <button
                        @click="showRegister = false"
                        class="border px-4 py-2 rounded"
                    >
                        Cancel
                    </button>

                    <button
                        @click="register"
                        class="bg-blue-600 text-white px-4 py-2 rounded"
                    >
                        Register
                    </button>
                </div>
            </div>
        </div>
        <!-- LOGIN MODAL -->
        <div
            v-if="showLogin"
            class="fixed inset-0 bg-black/40 flex items-center justify-center z-50"
        >
            <div class="bg-white p-6 rounded-xl w-96">
                <h2 class="text-xl mb-4">Login</h2>

                <input
                    v-model="loginForm.login"
                    placeholder="Email"
                    class="border w-full p-2 mb-2"
                />

                <input
                    type="password"
                    v-model="loginForm.password"
                    placeholder="Password"
                    class="border w-full p-2 mb-4"
                />

                <div class="flex justify-end gap-3">
                    <button
                        @click="showLogin = false"
                        class="border px-4 py-2 rounded"
                    >
                        Cancel
                    </button>

                    <button
                        @click="login"
                        class="bg-blue-600 text-white px-4 py-2 rounded"
                    >
                        Login
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from "vue";
import axios from "../api/axios";
import AdminMenu from "../components/AdminMenu.vue";
/*
USER STATE
*/

const user = ref(JSON.parse(localStorage.getItem("user")));

/*
REGISTER MODAL STATE
*/

const showRegister = ref(false);

/*
REGISTER FORM
*/

const registerForm = ref({
    first_name: "",
    last_name: "",
    phone: "",
    email: "",
    password: "",
});

/*
REGISTER REQUEST
*/

const register = async () => {
    const res = await axios.post("/register", registerForm.value);

    localStorage.setItem("token", res.data.token);
    localStorage.setItem("user", JSON.stringify(res.data.user));

    window.location.reload();
};

const showLogin = ref(false);

const loginForm = ref({
    login: "",
    password: "",
});
const login = async () => {
    try {
        const res = await axios.post("/login", loginForm.value);

        localStorage.setItem("token", res.data.token);
        localStorage.setItem("user", JSON.stringify(res.data.user));

        window.location.reload();
    } catch (e) {
        alert("Login failed");
    }
};
const logout = async () => {
    try {
        await axios.post("/logout");
    } catch (e) {}

    localStorage.removeItem("token");
    localStorage.removeItem("user");

    window.location.reload();
};
</script>
