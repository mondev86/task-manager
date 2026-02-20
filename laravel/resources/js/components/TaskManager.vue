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

        <!-- Horarios -->
        <div class="grid grid-cols-2 gap-4 mb-4">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">⏰ Hora de inicio</label>
            <input
              v-model="newTask.start_time"
              type="datetime-local"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
            />
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">⏱️ Hora de fin</label>
            <input
              v-model="newTask.end_time"
              type="datetime-local"
              class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
            />
          </div>
        </div>

        <!-- WhatsApp -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            📱 WhatsApp (con código de país, ej: 34612345678)
          </label>
          <input
            v-model="newTask.whatsapp_number"
            type="tel"
            placeholder="34612345678"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
          />
          <p class="text-xs text-gray-500 mt-1">
            Incluye código de país sin + (España: 34, México: 52, etc.)
          </p>
        </div>

        <!-- Email -->
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-2">
            📧 Email para recordatorios (opcional)
          </label>
          <input
            v-model="newTask.email"
            type="email"
            placeholder="tu@email.com"
            class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500"
          />
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

            <!-- Horarios -->
            <div v-if="task.start_time" class="mt-3 space-y-1">
              <div class="flex items-center gap-2 text-sm">
                <span class="text-gray-600">⏰ Inicio:</span>
                <span class="font-medium">{{ formatDateTime(task.start_time) }}</span>
              </div>
              <div v-if="task.end_time" class="flex items-center gap-2 text-sm">
                <span class="text-gray-600">⏱️ Fin:</span>
                <span class="font-medium">{{ formatDateTime(task.end_time) }}</span>
              </div>

              <!-- Contador de tiempo -->
              <div v-if="!task.completed && task.end_time" class="mt-2">
                <div class="text-sm">
                  <span class="font-semibold">⏳ Tiempo restante:</span>
                  <span :class="getTimeRemainingClass(task.end_time)" class="ml-2 font-bold">
                    {{ getTimeRemaining(task.end_time) }}
                  </span>
                </div>
              </div>
            </div>

            <!-- Botones de acción -->
            <div class="mt-3 flex gap-3">
              <button
                v-if="task.whatsapp_number && task.start_time"
                @click="sendWhatsAppReminder(task)"
                class="inline-flex items-center gap-2 bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition text-sm"
              >
                📱 Enviar por WhatsApp
              </button>

              <button
                v-if="task.start_time"
                @click="sendEmailReminder(task)"
                class="inline-flex items-center gap-2 bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition text-sm"
              >
                📧 Enviar por correo
              </button>
            </div>

            <p class="text-xs text-gray-400 mt-2">Creada: {{ formatDate(task.created_at) }}</p>
          </div>

          <!-- Acciones editar/eliminar -->
          <div class="task-actions flex flex-col gap-2 items-center">
            <button @click="editTask(task)" class="text-blue-500 hover:text-blue-700 text-2xl">✏️</button>
            <button @click="deleteTask(task.id)" class="text-red-500 hover:text-red-700 text-2xl">🗑️</button>
          </div>
        </div>
      </div>

      <div v-if="tasks.length === 0" class="text-center text-gray-400 py-12">
        No hay tareas. ¡Añade tu primera tarea! 🎯
      </div>
    </div>

    <!-- Modal de edición -->
    <div
      v-if="selectedTask"
      class="modal fixed inset-0 bg-black bg-opacity-30 flex items-center justify-center z-50"
    >
      <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h3 class="text-xl font-semibold mb-4">Editar tarea</h3>

        <div class="mb-3">
          <label class="block text-sm text-gray-600 mb-1">Título</label>
          <input v-model="selectedTask.title" placeholder="Título" class="w-full p-2 border rounded" />
        </div>

        <div class="mb-3">
          <label class="block text-sm text-gray-600 mb-1">Descripción</label>
          <textarea v-model="selectedTask.description" placeholder="Descripción" class="w-full p-2 border rounded"></textarea>
        </div>

        <div class="mb-3">
          <label class="block text-sm text-gray-600 mb-1">Email</label>
          <input v-model="selectedTask.email" type="email" placeholder="Correo" class="w-full p-2 border rounded" />
        </div>

        <div class="mb-3">
          <label class="block text-sm text-gray-600 mb-1">WhatsApp</label>
          <input v-model="selectedTask.whatsapp_number" placeholder="WhatsApp" class="w-full p-2 border rounded" />
        </div>

        <div class="mb-3 grid grid-cols-2 gap-2">
          <div>
            <label class="block text-sm text-gray-600 mb-1">Inicio</label>
            <input v-model="selectedTask.start_time" type="datetime-local" class="w-full p-2 border rounded" />
          </div>
          <div>
            <label class="block text-sm text-gray-600 mb-1">Fin</label>
            <input v-model="selectedTask.end_time" type="datetime-local" class="w-full p-2 border rounded" />
          </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
          <button
            @click="saveEditedTask"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
          >
            💾 Guardar
          </button>
          <button @click="selectedTask = null" class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">❌ Cancelar</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      tasks: [],
      newTask: {
        title: '',
        description: '',
        start_time: '',
        end_time: '',
        whatsapp_number: '',
        email: ''
      },
      currentTime: new Date(),
      selectedTask: null
    };
  },
  mounted() {
    this.fetchTasks();
    setInterval(() => {
      this.currentTime = new Date();
    }, 60000);
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
        const payload = { ...this.newTask };
        // Si los campos datetime están vacíos, enviarlos como null
        if (!payload.start_time) payload.start_time = null;
        if (!payload.end_time) payload.end_time = null;

        const response = await axios.post('/api/tasks', payload);
        this.tasks.unshift(response.data);
        this.newTask = {
          title: '',
          description: '',
          start_time: '',
          end_time: '',
          whatsapp_number: '',
          email: ''
        };
      } catch (error) {
        console.error('Error al añadir tarea:', error);
        alert('Error al añadir tarea. Verifica los datos.');
      }
    },

    async toggleTask(task) {
      try {
        const response = await axios.put(`/api/tasks/${task.id}`, {
          completed: !task.completed
        });
        // Si el backend devuelve el task actualizado
        if (response.data && typeof response.data.completed !== 'undefined') {
          task.completed = response.data.completed;
        } else {
          // fallback: invertir localmente
          task.completed = !task.completed;
        }
      } catch (error) {
        console.error('Error al actualizar tarea:', error);
      }
    },

    editTask(task) {
      // Clonar la tarea para editar sin mutar la lista hasta guardar
      this.selectedTask = { ...task };
      // Asegurar formato de datetime si vienen como null
      if (!this.selectedTask.start_time) this.selectedTask.start_time = '';
      if (!this.selectedTask.end_time) this.selectedTask.end_time = '';
    },

    async saveEditedTask() {
      if (!this.selectedTask) return;
      try {
        const payload = {
          title: this.selectedTask.title,
          description: this.selectedTask.description,
          email: this.selectedTask.email,
          whatsapp_number: this.selectedTask.whatsapp_number,
          start_time: this.selectedTask.start_time || null,
          end_time: this.selectedTask.end_time || null,
          completed: this.selectedTask.completed || false
        };

        await axios.put(`/api/tasks/${this.selectedTask.id}`, payload);

        // Cerrar modal y refrescar lista
        this.selectedTask = null;
        await this.fetchTasks();
        alert('✅ Tarea actualizada correctamente');
      } catch (error) {
        console.error('Error al actualizar la tarea:', error);
        alert(error.response?.data?.message || 'Error al actualizar la tarea');
      }
    },

    async deleteTask(id) {
      if (!confirm('¿Seguro que quieres eliminar esta tarea?')) return;
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter((task) => task.id !== id);
      } catch (error) {
        console.error('Error al eliminar tarea:', error);
        alert('Error al eliminar la tarea');
      }
    },

    // WhatsApp estándar (abre WhatsApp Web)
    async sendWhatsAppReminder(task) {
      if (!task.whatsapp_number) {
        alert('❌ No hay número de WhatsApp configurado');
        return;
      }

      try {
        const response = await axios.post(`/api/tasks/${task.id}/send-reminder`);
        if (response.data.success && response.data.whatsapp_url) {
          window.open(response.data.whatsapp_url, '_blank');
          alert('✅ ' + response.data.message);
        } else {
          alert('❌ No se pudo generar el enlace de WhatsApp');
        }
      } catch (error) {
        console.error('Error:', error);
        alert('❌ Error al generar enlace de WhatsApp');
      }
    },

    // Email a cualquier dirección
    async sendEmailReminder(task) {
      const email = prompt('¿A qué email enviar el recordatorio?', task.email || '');
      if (!email) return;

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        alert('Correo inválido');
        return;
      }

      if (!confirm(`¿Enviar recordatorio a ${email}?`)) return;

      try {
        const response = await axios.post(`/api/tasks/${task.id}/email-reminder`, {
          email: email
        });

        if (response.data.success) {
          alert('✅ ' + response.data.message);
        } else {
          alert('❌ ' + (response.data.message || 'Error al enviar correo'));
        }
      } catch (error) {
        console.error('Error:', error);
        alert(error.response?.data?.message || '❌ Error al enviar correo');
      }
    },

    formatDate(date) {
      return new Date(date).toLocaleDateString('es-ES', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },

    formatDateTime(date) {
      if (!date) return '';
      return new Date(date).toLocaleString('es-ES', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },

    getTimeRemaining(endTime) {
      const now = new Date();
      const end = new Date(endTime);
      const diff = end - now;

      if (diff <= 0) {
        return '¡Tiempo agotado!';
      }

      const days = Math.floor(diff / (1000 * 60 * 60 * 24));
      const hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
      const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));

      if (days > 0) {
        return `${days}d ${hours}h ${minutes}m`;
      } else if (hours > 0) {
        return `${hours}h ${minutes}m`;
      } else {
        return `${minutes}m`;
      }
    },

    getTimeRemainingClass(endTime) {
      const now = new Date();
      const end = new Date(endTime);
      const diff = end - now;
      const hours = diff / (1000 * 60 * 60);

      if (hours <= 0) return 'text-red-600';
      if (hours <= 1) return 'text-orange-600';
      if (hours <= 3) return 'text-yellow-600';
      return 'text-green-600';
    }
  }
};
</script>

<style scoped>
:root{
  --bg: #f8fafc;
  --card: #ffffff;
  --muted: #6b7280;
  --primary: #2563eb;
  --primary-600: #1e40af;
  --success: #16a34a;
  --danger: #dc2626;
  --glass: rgba(255,255,255,0.6);
}

/* Layout */
.container {
  background: var(--bg);
  min-height: 100vh;
  padding-bottom: 4rem;
}

/* Card */
.bg-white {
  /*background: linear-gradient(180deg, var(--card), #fbfdff);*/
  border: 1px solid rgba(15,23,42,0.04);
  transition: transform .18s ease, box-shadow .18s ease;
}
.bg-white:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 30px rgba(2,6,23,0.06);
}

/* Form fields */
input[type="text"],
input[type="email"],
input[type="tel"],
input[type="datetime-local"],
textarea {
  transition: box-shadow .12s ease, border-color .12s ease;
  box-shadow: none;
}
input:focus, textarea:focus {
  outline: none;
  border-color: rgba(37,99,235,0.9);
  box-shadow: 0 6px 18px rgba(37,99,235,0.08);
}

/* Buttons */
button {
  transition: transform .08s ease, box-shadow .12s ease, opacity .12s ease;
}
button:active { transform: translateY(1px); }

.btn-primary {
  background: linear-gradient(180deg,var(--primary),var(--primary-600));
  color: #fff;
  border-radius: 0.5rem;
  padding: .6rem 1rem;
  box-shadow: 0 6px 18px rgba(37,99,235,0.12);
}
.btn-primary:hover { filter: brightness(.98); }

.btn-ghost {
  background: transparent;
  border: 1px solid rgba(15,23,42,0.06);
  color: var(--muted);
  padding: .5rem .75rem;
  border-radius: .5rem;
}
.icon-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border-radius: 10px;
  background: linear-gradient(180deg, rgba(255,255,255,0.6), rgba(255,255,255,0.4));
  border: 1px solid rgba(15,23,42,0.04);
  cursor: pointer;
}
.icon-btn:hover { transform: translateY(-2px); box-shadow: 0 8px 20px rgba(2,6,23,0.04); }

/* Task title */
.text-xl {
  letter-spacing: -0.2px;
}

/* Actions column */
.task-actions {
  min-width: 64px;
}

/* Modal */
.modal {
  z-index: 60;
}
.modal .bg-white {
  border-radius: 12px;
  padding: 1.25rem;
  animation: pop .16s cubic-bezier(.2,.9,.2,1);
  max-height: 90vh;
  overflow-y: auto;
}
@keyframes pop {
  from { transform: translateY(8px) scale(.995); opacity: 0; }
  to { transform: translateY(0) scale(1); opacity: 1; }
}

/* Form inside modal */
.modal input, .modal textarea {
  border: 1px solid rgba(15,23,42,0.06);
  padding: .6rem;
  border-radius: .5rem;
}

/* Small helpers */
.small-muted { color: var(--muted); font-size: .85rem; }

/* Toasts */
.toast-container {
  position: fixed;
  right: 1rem;
  bottom: 1rem;
  z-index: 70;
  display: flex;
  flex-direction: column;
  gap: .5rem;
}
.toast {
  min-width: 260px;
  max-width: 360px;
  padding: .75rem 1rem;
  border-radius: 10px;
  color: #fff;
  display: flex;
  gap: .75rem;
  align-items: center;
  box-shadow: 0 10px 30px rgba(2,6,23,0.08);
  transform-origin: right bottom;
  animation: toast-in .18s cubic-bezier(.2,.9,.2,1);
}
@keyframes toast-in {
  from { transform: translateY(8px) scale(.98); opacity: 0; }
  to { transform: translateY(0) scale(1); opacity: 1; }
}
.toast.success { background: linear-gradient(90deg, #16a34a, #059669); }
.toast.error { background: linear-gradient(90deg, #ef4444, #dc2626); }
.toast.info { background: linear-gradient(90deg, #2563eb, #1e40af); }
.toast .msg { font-weight: 600; font-size: .95rem; }

/* Responsive tweaks */
@media (max-width: 640px) {
  .grid-cols-2 { grid-template-columns: 1fr; }
  .task-actions { flex-direction: row; gap: .5rem; }
  .icon-btn { width: 40px; height: 40px; }
}
</style>

