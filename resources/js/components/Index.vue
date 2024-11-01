<template>
    <div class="container mt-5">
        <h1 class="mb-4">Tasks</h1>

        <div v-if="successMessage" class="alert alert-success">
            {{ successMessage }}
        </div>

        <div class="row">
            <!-- Filter by status -->
            <div class="mb-4 d-flex align-items-center col-md-6 text-start">
                <label class="me-2">Filter by Status:</label>
                <select v-model="statusFilter" class="form-select w-auto">
                    <option value="All">All</option>
                    <option value="Completed">Completed</option>
                    <option value="Pending">Pending</option>
                </select>
            </div>
            <!-- Button Create -->
            <div class="mb-4 col-md-6 text-end">
                <router-link :to="{ name: 'Create' }" class="btn btn-success">
                    <i class="bi bi-plus-lg"></i> Create New Task
                </router-link>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Title</th>
                        <th scope="col">Description</th>
                        <th scope="col">Status</th>
                        <th scope="col" style="width: 30%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Filter tasks based on status -->
                    <tr v-for="task in filteredTasks" :key="task.id">
                        <td>{{ task.id }}</td>
                        <td>{{ task.title }}</td>
                        <td>{{ task.description }}</td>
                        <td>{{ task.status }}</td>
                        <td>
                            <!-- Toggle Status Button -->
                            <button class="btn btn-sm btn-outline-secondary me-2" @click="toggleTaskStatus(task)">
                                <i
                                    class="bi"
                                    :class="
                                        task.status === 'Pending' ? 'bi-check-circle' : 'bi-arrow-counterclockwise'
                                    "
                                ></i>
                                {{ task.status === 'Pending' ? 'Mark as Completed' : 'Mark as Pending' }}
                            </button>
                            <router-link
                                :to="{ name: 'Edit', params: { id: task.id } }"
                                class="btn btn-sm btn-outline-primary me-2"
                            >
                                <i class="bi bi-pencil-square me-1"></i> Edit
                            </router-link>
                            <button class="btn btn-sm btn-outline-danger" @click="openDeleteConfirmation(task.id)">
                                <i class="bi bi-trash me-1"></i> Delete
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
        class="modal fade"
        id="deleteConfirmationModal"
        tabindex="-1"
        aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true"
        ref="deleteModal"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to delete this task?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div
        class="modal fade"
        id="deleteConfirmationModal"
        tabindex="-1"
        aria-labelledby="deleteConfirmationModalLabel"
        aria-hidden="true"
        ref="deleteModal"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Delete</h5>
                    <button type="button" class="btn-close" @click="closeModal" aria-label="Close"></button>
                </div>
                <div class="modal-body">Are you sure you want to delete this task?</div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" @click="closeModal">Cancel</button>
                    <button type="button" class="btn btn-danger" @click="confirmDelete">Delete</button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
import { onMounted, ref, computed } from 'vue';
import axios from 'axios';

export default {
    setup () {
        const tasks = ref([]);
        const taskIdToDelete = ref(null);
        const deleteModal = ref(null);
        const successMessage = ref('');
        const statusFilter = ref('All'); // New filter state

        const fetchTasks = async () => {
            try {
                const uri = `${import.meta.env.VITE_API_URL}/tasks`;
                const response = await axios.get(uri);
                tasks.value = response.data;
            } catch (error) {
                console.error('Error fetching tasks:', error);
            }
        };

        // Toggle task status
        const toggleTaskStatus = async (task) => {
            const newStatus = task.status === 'Pending' ? 'Completed' : 'Pending';
            try {
                const uri = `${import.meta.env.VITE_API_URL}/tasks/${task.id}`;
                await axios.patch(uri, { title: task.title, description: task.description, status: newStatus });
                task.status = newStatus;
                successMessage.value = `Task marked as ${newStatus}!`;
                setTimeout(() => {
                    successMessage.value = '';
                }, 1000);
            } catch (error) {
                console.error('Error updating task status:', error);
            }
        };

        // Computed property to filter tasks based on selected status
        const filteredTasks = computed(() => {
            if (statusFilter.value === 'All') return tasks.value;
            return tasks.value.filter((task) => task.status === statusFilter.value);
        });

        const openDeleteConfirmation = (id) => {
            taskIdToDelete.value = id;
            const modalInstance = new bootstrap.Modal(deleteModal.value);
            modalInstance.show();
        };

        const closeModal = () => {
            const modalInstance = bootstrap.Modal.getInstance(deleteModal.value);
            if (modalInstance) {
                modalInstance.hide();
            }
        };

        const confirmDelete = async () => {
            if (taskIdToDelete.value !== null) {
                await deleteTask(taskIdToDelete.value);
                closeModal();
            }
        };

        const deleteTask = async (id) => {
            try {
                const uri = `${import.meta.env.VITE_API_URL}/tasks/${id}`;
                await axios.delete(uri);
                tasks.value = tasks.value.filter((task) => task.id !== id);
                successMessage.value = 'Task deleted successfully!';
                setTimeout(() => {
                    successMessage.value = '';
                }, 1000);
            } catch (error) {
                console.error('Error deleting task:', error);
            }
        };

        onMounted(fetchTasks);

        return {
            tasks,
            openDeleteConfirmation,
            confirmDelete,
            deleteTask,
            deleteModal,
            closeModal,
            successMessage,
            statusFilter,
            filteredTasks,
            toggleTaskStatus
        };
    }
};
</script>
