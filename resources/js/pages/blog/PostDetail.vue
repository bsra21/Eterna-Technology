<template>
    <div class="max-w-4xl mx-auto p-6">
        <!-- Post Card -->
        <div class="bg-white rounded-2xl shadow-lg p-8 mb-10">
            <h1 class="text-3xl font-bold mb-6">
                {{ post?.title }}
            </h1>
            <button v-if="user?.id === post?.user_id" @click="editPost">
                Edit
            </button>

            <button
                v-if="user?.role === 'admin' || user?.id === post?.user_id"
                @click="deletePost"
            >
                Delete
            </button>
            <div class="prose max-w-none text-gray-700 leading-relaxed">
                {{ post?.content }}
            </div>

            <div class="mt-6 text-sm text-gray-400">
                Published: {{ formatDate(post?.created_at) }}
            </div>
        </div>

        <!-- Comment Section -->
        <div class="bg-white rounded-2xl shadow-lg p-8">
            <h2 class="text-xl font-semibold mb-6 border-b pb-3">
                Comments ({{ comments.length }})
            </h2>

            <!-- Comment List -->
            <div
                v-for="c in comments"
                :key="c.id"
                class="mb-5 p-4 border rounded-xl hover:bg-gray-50 transition"
            >
                <div class="flex justify-between items-center mb-2">
                    <span class="font-semibold text-gray-800">
                        {{ c.user?.first_name || "Guest" }}
                    </span>

                    <span class="text-xs text-gray-400">
                        {{ formatDate(c.created_at) }}
                    </span>
                </div>

                <p class="text-gray-600 text-sm">
                    {{ c.content }}
                </p>
            </div>

            <!-- Comment Input -->
            <div v-if="user" class="mt-8">
                <textarea
                    v-model="newComment"
                    class="w-full border rounded-xl p-4 focus:ring-2 focus:ring-blue-400 outline-none"
                    placeholder="Write your comment..."
                ></textarea>

                <button
                    @click="submitComment"
                    class="mt-4 px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition"
                >
                    Send Comment
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            post: null,
            comments: [],
            newComment: "",
            user: JSON.parse(localStorage.getItem("user")),
        };
    },

    mounted() {
        this.fetchPost();
        this.fetchComments();
    },

    methods: {
        async fetchPost() {
            const res = await axios.get(
                "http://127.0.0.1:8000/api/posts/" + this.$route.params.id,
            );

            this.post = res.data;
        },

        async fetchComments() {
            const postId = this.$route.params.id;

            try {
                const res = await axios.get(
                    `http://127.0.0.1:8000/api/posts/${postId}/comments`,
                );
                this.comments = res.data;
            } catch (e) {
                console.error(e);
            }
        },

        async submitComment() {
            try {
                await axios.post(
                    "http://127.0.0.1:8000/api/posts/" +
                        this.$route.params.id +
                        "/comments",
                    {
                        content: this.newComment,
                    },
                );

                this.newComment = "";
                this.fetchComments();
            } catch (e) {
                console.log(e);
            }
        },
        formatDate(date) {
            if (!date) return "";

            return new Date(date).toLocaleString();
        },
    },
};
</script>
```
