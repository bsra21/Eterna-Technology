```vue
<template>
    <div class="container">
        <div v-if="loading">Loading posts...</div>

        <div v-for="post in posts" :key="post.id" class="post-card">
            <router-link :to="'/post/' + post.id">
                <h2 class="text-xl font-bold">
                    {{ post.title }}
                </h2>
            </router-link>

            <p>{{ truncate(post.content) }}</p>

            <small> Published: {{ formatDate(post.published_at) }} </small>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    data() {
        return {
            posts: [],
            loading: true,
        };
    },

    mounted() {
        this.fetchPosts();
    },

    methods: {
        async fetchPosts() {
            try {
                const res = await axios.get("http://127.0.0.1:8000/api/posts");

                this.posts = res.data.data ?? res.data;
            } catch (e) {
                console.log(e);
            } finally {
                this.loading = false;
            }
        },

        truncate(text) {
            if (!text) return "";
            return text.substring(0, 120) + "...";
        },

        formatDate(date) {
            return new Date(date).toLocaleDateString();
        },
    },
};
</script>
<style>
.container {
    max-width: 900px;
    margin: auto;
}

.post-card {
    border: 1px solid #ddd;
    padding: 20px;
    margin-bottom: 20px;
    border-radius: 6px;
}
</style>

```
