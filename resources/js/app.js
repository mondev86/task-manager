import './bootstrap';
import { createApp } from 'vue';
import TaskManager from '../../components/TaskManager.vue';
import axios from 'axios';

window.axios = axios;

axios.defaults.withCredentials = true;
axios.defaults.withXRequestedWith = 'XMLHttpRequest';
axios.interceptors.request.use((config) => {
    const token = localStorage.getItem('token');
    if (token) {
        config.headers.Authorization = `Bearer ${token}`;
    }
    return config;
});

// Register the component globally
const app = createApp({});
app.component('task-manager', TaskManager);
app.mount('#app');
