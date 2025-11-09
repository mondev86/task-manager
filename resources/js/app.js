import './bootstrap';
import { createApp } from 'vue';
import TaskManager from './components/TaskManager.vue';
input: ['resources/css/app.css', 'resources/js/app.js']
import axios from 'axios';
import Echo from 'laravel-echo';

window.axios = axios;

const app = createApp(TaskManager);
app.mount('#app');



window.Echo = new Echo({
    broadcaster: 'reverb',
    host: window.location.hostname + ':8080'
});
