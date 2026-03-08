<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-6">Posts Management</h1>
        <button
            @click="showModal = true"
            class="bg-blue-600 text-white px-4 py-2 rounded-xl mb-6"
        >
            New Post
        </button>
        <PostCreateModal
            :visible="showModal"
            @close="showModal = false"
            @created="loadPosts"
        />
        <EditPostModal
            :visible="editModal"
            :post="selectedPost"
            @close="editModal = false"
            @updated="loadPosts"
        />
        <div class="bg-white rounded-2xl shadow p-6">
            <table class="w-full">
                <thead>
                    <tr class="border-b">
                        <th class="text-left p-3">Title</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Score</th>
                        <th>Author</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="post in posts" :key="post.id" class="border-b">
                        <td class="p-3">{{ post.title }}</td>
                        <td>{{ post.status }}</td>
                        <td>{{ post.views }}</td>
                        <td>{{ post.score }}</td>
                        <td class="p-4">
                            {{ post.user.first_name }} {{ post.user.last_name }}
                        </td>
                        <td>
                            <span
                                class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs"
                            >
                                {{ post.user.role }}
                            </span>
                        </td>
                        <td>
                            <button
                                @click="editPost(post)"
                                class="text-blue-600 mr-3 border border-blue-600"
                            >
                                Edit
                            </button>

                            <button
                                @click="remove(post.id)"
                                class="bg-red-500 text-white px-3 py-1 rounded"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script setup>
import { ref, onMounted } from "vue";
import axios from "../../api/axios";
import PostCreateModal from "../../components/PostCreateModal.vue";
import EditPostModal from "../../components/EditPostModal.vue";
import { deletePost } from "../../api/posts";

const showModal = ref(false);
const editModal = ref(false);
const selectedPost = ref(null);
const posts = ref([]);

const loadPosts = async () => {
    try {
        const res = await axios.get("/admin/posts");
        posts.value = res.data.data || res.data; // backend yapısına göre
    } catch (error) {
        console.error(error);
    }
};

const remove = async (id) => {
    if (!confirm("Delete post?")) return;

    await deletePost(id);
    await loadPosts();
};

const editPost = (post) => {
    selectedPost.value = post;
    editModal.value = true;
};

onMounted(loadPosts);
</script>
