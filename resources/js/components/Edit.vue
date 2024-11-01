<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h1 class="card-title">Edit Task</h1>
            </div>
            <div class="card-body">
                <!-- Success Message -->
                <div v-if="successMessage" class="alert alert-success">
                    {{ successMessage }}
                </div>

                <!-- Error Message -->
                <div v-if="errorMessage" class="alert alert-danger">
                    {{ errorMessage }}
                </div>

                <form @submit.prevent="updateTask">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="taskTitle" class="form-label">Title:</label>
                            <input
                                type="text"
                                id="taskTitle"
                                class="form-control"
                                v-model="task.title"
                                placeholder="Enter title"
                                required
                            />
                        </div>
                        <div class="col-md-4">
                            <label for="taskDescription" class="form-label">Description:</label>
                            <input
                                type="text"
                                id="taskDescription"
                                class="form-control"
                                v-model="task.description"
                                placeholder="Enter description"
                                required
                            />
                        </div>
                        <div class="col-md-4">
                            <label for="taskStatus" class="form-label">Status:</label>
                            <select id="taskStatus" class="form-control" v-model="task.status" required>
                                <option value="" disabled selected>Enter status</option>
                                <option value="Completed">Completed</option>
                                <option value="Pending">Pending</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mt-4 col-md-6 text-start">
                            <router-link :to="{ name: 'Index' }" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left-circle"></i> Return to Tasks List
                            </router-link>
                        </div>
                        <div class="mt-4 col-md-6 text-end">
                            <button type="submit" class="btn btn-primary px-4">Update Task</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<style>
.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
}
</style>

<script>
import { onMounted, reactive, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

export default {
    setup () {
        const task = reactive({ title: '', description: '', status: '' });
        const route = useRoute();
        const router = useRouter();
        const successMessage = ref('');
        const errorMessage = ref('');

        const getTask = async () => {
            try {
                const uri = `${import.meta.env.VITE_API_URL}/tasks/${route.params.id}/edit`;
                const response = await axios.get(uri);
                Object.assign(task, response.data);
            }
            catch (error) {
                errorMessage.value = 'Failed to fetch task details. Please try again.';
                console.error("Failed to fetch task:", error);
            }
        };

        const updateTask = async () => {
            const uri = `${import.meta.env.VITE_API_URL}/tasks/${route.params.id}`;
            try {
                await axios.patch(uri, task);
                successMessage.value = 'Task Updated Successfully!';
                errorMessage.value = ''; // Clear any previous error message
                setTimeout(() => {
                    successMessage.value = '';
                    router.push({ name: 'Index' });
                }, 1000);
            } catch (error) {
                errorMessage.value = error.response?.data?.message || 'Failed to update task. Please try again.';
                console.error("Error updating task:", error);

                // Clear the error message after a few seconds
                setTimeout(() => {
                    errorMessage.value = '';
                }, 3000);
            }
        };

        onMounted(getTask);

        return {
            task,
            updateTask,
            successMessage,
            errorMessage
        };
    }
}
</script>
