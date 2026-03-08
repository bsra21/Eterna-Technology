<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-6">Comments</h1>

        <table class="w-full bg-white rounded-xl shadow">
            <thead>
                <tr class="border-b bg-gray-50">
                    <th class="p-3 text-left">Comment</th>
                    <th>Status</th>
                    <th>Post ID</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                <tr
                    v-for="comment in comments"
                    :key="comment.id"
                    class="border-b"
                >
                    <td class="p-3">{{ comment.content }}</td>
                    <td>{{ comment.status }}</td>
                    <td>{{ comment.post_id }}</td>
                    <td>
                        <button
                            @click="remove(comment.id)"
                            class="text-red-600 ml-3"
                        >
                            Delete
                        </button>
                    </td>
                    <td>
                        <button
                            v-if="comment.status === 'pending'"
                            @click="approve(comment.id)"
                            class="text-green-600"
                        >
                            Approve
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import axios from "../../api/axios";
import { ref, onMounted } from "vue";

const comments = ref([]);

const loadComments = async () => {
    const res = await axios.get("/admin/comments");
    comments.value = res.data;
};

const approve = async (id) => {
    try {
        await axios.patch(`/comments/${id}/approve`);
        loadComments();
    } catch (e) {
        alert("Update failed");
    }
};

const remove = async (id) => {
    try {
        await axios.delete(`/comments/${id}`);
        loadComments();
    } catch (e) {
        alert("Delete soft failed");
    }
};
onMounted(loadComments);
</script>
