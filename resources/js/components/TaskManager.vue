<template>
    <div class="container mx-auto p-6 max-w-4xl">
        <h1 class="text-4xl font-bold text-gray-800 mb-8">📝 Task Manager</h1>

        <!-- Formulario para añadir tareas -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <h2 class="text-2xl font-semibold mb-4">Nueva Tarea</h2>
            <form @submit.prevent="addTask">
                <div class="mb-4">
                    <input
                        v-model="newTask.title"
                        type="text"
                        placeholder="Título de la tarea"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        required
                    />
                </div>
                <div class="mb-4">
                    <textarea
                        v-model="newTask.description"
                        placeholder="Descripción (opcional)"
                        class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
                        rows="3"
                    ></textarea>
                </div>
                <button
                    type="submit"
                    class="w-full bg-blue-500 text-white py-3 rounded-lg hover:bg-blue-600 transition"
                >
                    ➕ Añadir Tarea
                </button>
            </form>
        </div>

        <!-- Lista de tareas -->
        <div class="space-y-4">
            <div
                v-for="task in tasks"
                :key="task.id"
                class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition"
            >
                <div class="flex items-start gap-4">
                    <input
                        type="checkbox"
                        :checked="task.completed"
                        @change="toggleTask(task)"
                        class="mt-1 w-5 h-5 cursor-pointer"
                    />
                    <div class="flex-1">
                        <h3
                            :class="task.completed ? 'line-through text-gray-400' : 'text-gray-800'"
                            class="text-xl font-semibold"
                        >
                            {{ task.title }}
                        </h3>
                        <p
                            v-if="task.description"
                            :class="task.completed ? 'text-gray-300' : 'text-gray-600'"
                            class="mt-2"
                        >
                            {{ task.description }}
                        </p>
                        <p class="text-sm text-gray-400 mt-2">
                            {{ formatDate(task.created_at) }}
                        </p>
                    </div>
                    <button
                        @click="deleteTask(task.id)"
                        class="text-red-500 hover:text-red-700 text-2xl"
                    >
                        🗑️
                    </button>
                </div>
            </div>

            <div v-if="tasks.length === 0" class="text-center text-gray-400 py-12">
                No hay tareas. ¡Añade tu primera tarea! 🎯
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'

export default {
    data() {
        return {
            tasks: [],
            newTask: {
                title: '',
                description: ''
            }
        }
    },
   mounted() {
    // Cargar tareas iniciales
    this.fetchTasks();

    // Refrescar cada 5 segundos vía AJAX
    setInterval(() => {
        this.fetchTasks();
    }, 3000);


        // Escuchar eventos en tiempo real
        window.Echo.channel('tasks')
            .listen('TaskCreated', (e) => {
                this.tasks.unshift(e.task);
            })
            .listen('TaskDeleted', (e) => {
                this.tasks = this.tasks.filter(task => task.id !== e.task.id);
            })
            .listen('TaskUpdated', (e) => {
                const index = this.tasks.findIndex(t => t.id === e.task.id);
                if (index !== -1) {
                    this.tasks[index] = e.task;
                }
            });
    },
    methods: {
        async fetchTasks() {
            try {
                const response = await axios.get('/api/tasks');
                this.tasks = response.data;
            } catch (error) {
                console.error('Error al cargar tareas:', error);
            }
        },
        async addTask() {
            try {
                await axios.post('/api/tasks', this.newTask);
                // No añadimos manualmente: TaskCreated lo hará en tiempo real
                this.newTask = { title: '', description: '' };
            } catch (error) {
                console.error('Error al añadir tarea:', error);
            }
        },
        async toggleTask(task) {
            try {
                await axios.put(`/api/tasks/${task.id}`, {
                    completed: !task.completed
                });
                // TaskUpdated se encargará de reflejar el cambio
            } catch (error) {
                console.error('Error al actualizar tarea:', error);
            }
        },
        async deleteTask(id) {
            if (confirm('¿Seguro que quieres eliminar esta tarea?')) {
                try {
                    await axios.delete(`/api/tasks/${id}`);
                    // TaskDeleted se encargará de actualizar la lista
                } catch (error) {
                    console.error('Error al eliminar tarea:', error);
                }
            }
        },
        formatDate(date) {
            return new Date(date).toLocaleDateString('es-ES', {
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            });
        }
    }
}
</script>

