<template>
    <div class="container mt-5">
        <div class="card shadow-lg">
            <div class="card-header bg-gradient-primary text-white">
                <h1 class="card-title">Create Task</h1>
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

                <form @submit.prevent="addTask">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label for="taskTitle" class="form-label">Title:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="taskTitle"
                                v-model="task.title"
                                placeholder="Enter title"
                                required
                            />
                        </div>
                        <div class="col-md-4">
                            <label for="taskDescription" class="form-label">Description:</label>
                            <input
                                type="text"
                                class="form-control"
                                id="taskDescription"
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
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bi bi-plus-lg"></i> Add Task
                            </button>
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
import { reactive, ref } from 'vue';
import { useRouter } from 'vue-router';

export default {
    setup () {
        const task = reactive({ title: '', description: '', status: '', });
        const router = useRouter();
        const successMessage = ref('');
        const errorMessage = ref('');

        const addTask = async () => {
            const uri = `${import.meta.env.VITE_API_URL}/tasks`;
            try {
                await axios.post(uri, task);
                successMessage.value = 'Task added successfully!';
                errorMessage.value = ''; // Clear any previous error
                setTimeout(() => {
                    successMessage.value = '';
                    router.push({ title: 'Index' });
                }, 1000);
            } catch (error) {
                // Set error message
                errorMessage.value = error.response?.data?.message || 'An error occurred while adding the task.';
                console.error('Error adding task:', error);

                // Clear error message after a few seconds
                setTimeout(() => {
                    errorMessage.value = '';
                }, 3000);
            }
        };

        return {
            task,
            addTask,
            successMessage,
            errorMessage
        };
    }
}
</script>
