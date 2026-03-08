<template>
    <div class="p-8">
        <h1 class="text-2xl font-bold mb-6">Categories</h1>

        <div class="bg-white rounded-2xl shadow">
            <table class="w-full">
                <thead class="border-b bg-gray-50">
                    <tr>
                        <th class="p-4 text-left">Name</th>
                        <th class="p-4">Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <tr
                        v-for="cat in categories"
                        :key="cat.id"
                        class="border-b hover:bg-gray-50"
                    >
                        <td class="p-4">{{ cat.name }}</td>

                        <td class="p-4 space-x-3">
                            <button
                                @click="edit(cat)"
                                class="text-blue-600 text-sm"
                            >
                                Edit
                            </button>

                            <button
                                @click="remove(cat.id)"
                                class="text-red-600 text-sm"
                            >
                                Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="mb-6 flex gap-3">
            <input
                v-model="form.name"
                placeholder="New category name"
                class="border rounded-lg p-2 flex-1"
            />

            <button
                @click="saveCategory"
                class="bg-blue-600 text-white px-4 rounded-lg"
            >
                Add Category
            </button>
        </div>
    </div>

    <div
        v-if="editMode"
        class="fixed inset-0 bg-black/40 flex items-center justify-center"
    >
        <div class="bg-white p-6 rounded-xl w-96">
            <h2 class="text-xl mb-4">Edit Category</h2>

            <input v-model="form.name" class="border w-full p-2 rounded" />

            <div class="flex justify-end gap-3 mt-5">
                <button
                    @click="editMode = false"
                    class="px-4 py-2 border rounded"
                >
                    Cancel
                </button>

                <button
                    @click="updateCategory"
                    class="bg-blue-600 text-white px-4 py-2 rounded"
                >
                    Update
                </button>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from "../../api/axios";
import { ref, onMounted } from "vue";

const editMode = ref(false);

const form = ref({
    id: null,
    name: "",
});

const categories = ref([]);

const loadCategories = async () => {
    const res = await axios.get("/categories");
    categories.value = res.data;
};

const remove = async (id) => {
    await axios.delete(`/categories/${id}`);
    loadCategories();
};
const edit = (cat) => {
    editMode.value = true;

    form.value.id = cat.id;
    form.value.name = cat.name;
};
const updateCategory = async () => {
    await axios.put(`/categories/${form.value.id}`, {
        name: form.value.name,
    });

    editMode.value = false;
    form.value.name = "";

    loadCategories();
};
const saveCategory = async () => {
    try {
        await axios.post("/categories", {
            name: form.value.name,
        });

        form.value.name = "";

        loadCategories();
    } catch (e) {
        console.log(e);
    }
};
onMounted(loadCategories);
</script>
